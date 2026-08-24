<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\PelakuUsahaRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\JenisUsaha;
use App\Models\Kelurahan;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use App\Services\PelakuUsahaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PelakuUsahaController extends Controller
{
    public function __construct(protected PelakuUsahaService $service)
    {
    }

    public function index()
    {
        $provinsis = Provinsi::orderBy('nama')->get();
        $jenisUsahas = JenisUsaha::orderBy('nama')->get();

        return view('pelaku-usaha.index', compact('provinsis', 'jenisUsahas'));
    }

    public function data(Request $request)
    {
        $query = PelakuUsaha::with(['jenisUsaha', 'provinsi', 'kabupaten', 
                'baWasPrls:id,pelaku_usaha_id,nomor_ba', 
                'baWasAlses:id,pelaku_usaha_id,nomor_ba', 
                'baReklamasis:id,pelaku_usaha_id,nomor_ba', 
                'baPpks:id,pelaku_usaha_id,nomor_ba', 
                'baPencemarans:id,pelaku_usaha_id,nomor_ba'])
            ->withExists(['baWasPrls', 'baWasAlses', 'baReklamasis', 'baPpks', 'baPencemarans'])
            ->filter($request->only(['search', 'provinsi_id', 'kabupaten_id', 'jenis_usaha_id', 'status']));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jenis_usaha', fn ($row) => $row->jenisUsaha->nama ?? '-')
            ->addColumn('jenis_pengawasan', function ($row) {
                $badges = [];
                if ($row->ba_was_prls_exists) $badges[] = '<span class="badge bg-primary">BA WAS PRL</span>';
                if ($row->ba_was_alses_exists) $badges[] = '<span class="badge bg-info">BA WAS ALSE</span>';
                if ($row->ba_reklamasis_exists) $badges[] = '<span class="badge bg-success">BA REKLAMASI</span>';
                if ($row->ba_ppks_exists) $badges[] = '<span class="badge bg-warning text-dark">BA PPK</span>';
                if ($row->ba_pencemarans_exists) $badges[] = '<span class="badge bg-danger">BA PENCEMARAN</span>';
                
                if (empty($badges)) return '-';
                
                return '<div class="d-flex flex-wrap gap-1">' . implode('', $badges) . '</div>';
            })
            ->addColumn('wilayah', function ($row) {
                if ($row->kabupaten_id || $row->provinsi_id) {
                    $kab = $row->kabupaten->nama ?? '';
                    $prov = $row->provinsi->nama ?? '';
                    return trim($kab . ($kab && $prov ? ', ' : '') . $prov);
                }
                return $row->alamat ?: '-';
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'aktif' => 'success',
                    'tidak_aktif' => 'secondary',
                    'dalam_proses' => 'warning',
                    'bermasalah' => 'danger',
                ];
                $color = $map[$row->status] ?? 'secondary';
                $label = ucwords(str_replace('_', ' ', $row->status));

                return "<span class=\"badge bg-{$color}\">{$label}</span>";
            })
            ->addColumn('aksi', function ($row) {
                return view('pelaku-usaha.partials.aksi', compact('row'))->render();
            })
            ->rawColumns(['status_badge', 'aksi', 'jenis_pengawasan'])
            ->make(true);
    }



    public function show(PelakuUsaha $pelakuUsaha)
    {
        $pelakuUsaha->load(['jenisUsaha', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dokumens', 'baWasPrls', 'baWasAlses']);

        return view('pelaku-usaha.show', compact('pelakuUsaha'));
    }

    public function edit(PelakuUsaha $pelakuUsaha)
    {
        $this->authorize('update', $pelakuUsaha);

        $jenisUsahas = JenisUsaha::orderBy('nama')->get();
        $provinsis = Provinsi::orderBy('nama')->get();
        $kabupatens = Kabupaten::where('provinsi_id', $pelakuUsaha->provinsi_id)->orderBy('nama')->get();
        $kecamatans = Kecamatan::where('kabupaten_id', $pelakuUsaha->kabupaten_id)->orderBy('nama')->get();
        $kelurahans = Kelurahan::where('kecamatan_id', $pelakuUsaha->kecamatan_id)->orderBy('nama')->get();

        return view('pelaku-usaha.edit', compact('pelakuUsaha', 'jenisUsahas', 'provinsis', 'kabupatens', 'kecamatans', 'kelurahans'));
    }

    public function update(PelakuUsahaRequest $request, PelakuUsaha $pelakuUsaha)
    {
        $this->authorize('update', $pelakuUsaha);

        $data = $request->validated();
        unset($data['foto_lokasi'], $data['dokumen'], $data['jenis_dokumen']);

        $this->service->update(
            $pelakuUsaha,
            $data,
            $request->file('foto_lokasi'),
            $request->file('dokumen', []),
            $request->input('jenis_dokumen', [])
        );

        return redirect()->route('pelaku-usaha.index')->with('success', 'Data pelaku usaha berhasil diperbarui.');
    }

    public function destroy(PelakuUsaha $pelakuUsaha)
    {
        $this->authorize('delete', $pelakuUsaha);

        $this->service->delete($pelakuUsaha);

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PelakuUsahaExport($request->all()),
            'pelaku-usaha-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $data = PelakuUsaha::with(['jenisUsaha', 'provinsi', 'kabupaten'])
            ->withExists(['baWasPrls', 'baWasAlses', 'baReklamasis', 'baPpks', 'baPencemarans'])
            ->filter($request->all())
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelaku-usaha.export-pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->download('pelaku-usaha-'.now()->format('Ymd-His').'.pdf');
    }



    public function downloadDokumen(int $dokumenId)
    {
        $dokumen = \App\Models\PelakuUsahaDokumen::findOrFail($dokumenId);

        return Storage::disk('public')->download($dokumen->path_file, $dokumen->nama_file);
    }
}
