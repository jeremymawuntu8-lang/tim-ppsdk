<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DokumenPelakuUsaha;
use App\Models\PelakuUsahaDokumen;
use App\Models\PelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DokumenController extends Controller
{
    public function index()
    {
        $pelakuUsahas = PelakuUsaha::where('status', 'aktif')->orderBy('nama_perusahaan')->get();
        return view('dokumen.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $manualDocs = DokumenPelakuUsaha::with('pelakuUsaha')
            ->whereHas('pelakuUsaha', function($q) {
                $q->where('status', 'aktif');
            })
            ->when($request->jenis_dokumen, fn ($q, $v) => $q->where('jenis_dokumen', $v))
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'perusahaan' => $doc->pelakuUsaha->nama_perusahaan ?? '-',
                    'nama_pic' => $doc->nama_pic ?? '-',
                    'jabatan' => $doc->jabatan ?? '-',
                    'jenis_dokumen' => $doc->jenis_dokumen,
                    'tanggal' => $doc->tanggal_upload ? Carbon::parse($doc->tanggal_upload)->format('d/m/Y') : '-',
                    'tanggal_raw' => $doc->tanggal_upload,
                    'is_manual' => true,
                    'file_path' => $doc->path_file,
                ];
            });

        $regDocs = \App\Models\Company::where('status', 'active')
            ->whereNotNull('file_dokumen')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'perusahaan' => $company->nama_perusahaan ?? '-',
                    'nama_pic' => $company->nama_penanggung_jawab ?? '-',
                    'jabatan' => $company->jabatan_penanggung_jawab ?? '-',
                    'jenis_dokumen' => $company->dokumen_diunggah ?: 'Dokumen Pengajuan',
                    'tanggal' => $company->created_at ? $company->created_at->format('d/m/Y') : '-',
                    'tanggal_raw' => $company->created_at,
                    'is_manual' => false,
                    'file_path' => $company->file_dokumen,
                ];
            });

        $allDocs = $manualDocs->concat($regDocs)->sortByDesc('tanggal_raw')->values();

        return DataTables::of($allDocs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($r) {
                $viewUrl = asset('storage/' . $r['file_path']);
                $downloadUrl = $r['is_manual'] ? route('dokumen.download', $r['id']) : route('dokumen.company.download', $r['id']);
                $isManualFlag = $r['is_manual'] ? 'true' : 'false';

                return '<div class="btn-group">
                    <a href="'.$viewUrl.'" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat"><i class="fas fa-external-link-alt"></i></a>
                    <a href="'.$downloadUrl.'" class="btn btn-sm btn-outline-primary" title="Unduh"><i class="fas fa-download"></i></a>
                    <button type="button" onclick="hapusDokumen('.$r['id'].', '.$isManualFlag.')" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pelaku_usaha_id' => ['required', 'string'],
            'nama_pic' => ['required', 'string'],
            'jabatan' => ['required', 'string'],
            'nomor_hp' => ['required', 'string'],
            'email' => ['required', 'email'],
            'jenis_dokumen' => ['required', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $pelakuUsahaInput = $data['pelaku_usaha_id'];
        $pelakuUsaha = \App\Models\PelakuUsaha::find($pelakuUsahaInput);

        if (!$pelakuUsaha) {
            // Jika ID tidak ditemukan, cari berdasarkan nama perusahaan yang sama
            $pelakuUsaha = \App\Models\PelakuUsaha::where('nama_perusahaan', $pelakuUsahaInput)->first();
            
            if (!$pelakuUsaha) {
                // Jika masih tidak ada, berarti user mengetikkan nama perusahaan baru
                $pelakuUsaha = \App\Models\PelakuUsaha::create([
                    'nama_perusahaan' => $pelakuUsahaInput,
                    'status' => 'aktif',
                    'created_by' => auth()->id(),
                ]);
            }
        }

        $data['pelaku_usaha_id'] = $pelakuUsaha->id;

        $file = $request->file('file');
        $data['nama_file'] = $file->getClientOriginalName();
        $data['path_file'] = $file->store('dokumen-pelaku-usaha', 'public');
        $data['tanggal_upload'] = now();
        $data['uploaded_by'] = auth()->id();
        unset($data['file']);

        DokumenPelakuUsaha::create($data);
        ActivityLog::catat('Upload', 'Dokumen', 'Mengunggah dokumen pelaku usaha');

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(DokumenPelakuUsaha $dokumen)
    {
        if ($dokumen->path_file) {
            Storage::disk('public')->delete($dokumen->path_file);
        }
        ActivityLog::catat('Hapus', 'Dokumen', 'Menghapus dokumen pelaku usaha');
        $dokumen->delete();

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
    }

    public function download(DokumenPelakuUsaha $dokumen)
    {
        ActivityLog::catat('Download', 'Dokumen', "Mengunduh dokumen: {$dokumen->nama_file}");
        return Storage::disk('public')->download($dokumen->path_file, $dokumen->nama_file);
    }

    public function downloadCompany(\App\Models\Company $company)
    {
        if (!$company->file_dokumen) {
            abort(404);
        }
        ActivityLog::catat('Download', 'Dokumen', "Mengunduh dokumen pengajuan perusahaan: {$company->nama_perusahaan}");
        $fileName = 'Dokumen_Pengajuan_' . str_replace(' ', '_', $company->nama_perusahaan) . '.pdf';
        return Storage::disk('public')->download($company->file_dokumen, $fileName);
    }

    public function destroyCompany(\App\Models\Company $company)
    {
        if ($company->file_dokumen && Storage::disk('public')->exists($company->file_dokumen)) {
            Storage::disk('public')->delete($company->file_dokumen);
        }
        
        $company->update([
            'file_dokumen' => null,
            'dokumen_diunggah' => null,
        ]);

        ActivityLog::catat('Hapus', 'Dokumen', 'Menghapus dokumen pengajuan perusahaan: ' . $company->nama_perusahaan);

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);
    }
}
