<?php

namespace App\Http\Controllers;

use App\Models\SuratPeringatan;
use App\Models\PelakuUsaha;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\SuratPeringatanExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratPeringatanController extends Controller
{
    public function export()
    {
        return Excel::download(new SuratPeringatanExport, "Data_Surat_Peringatan_".date('Y-m-d_H-i-s').".xlsx");
    }

    public function indexData(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratPeringatan::with('pelakuUsaha')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subjek_hukum', function($row){
                    return $row->pelakuUsaha ? $row->pelakuUsaha->nama_perusahaan : ($row->contact_person ?? '-');
                })
                ->addColumn('action', function($row) {
                    $editBtn = '<a href="'.route('surat-peringatan.edit', $row->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $deleteBtn = '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" title="Hapus"><i class="fas fa-trash"></i></button>';
                    
                    return '<div class="d-flex gap-1 justify-content-center">'.$editBtn.$deleteBtn.'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function index()
    {
        return view('surat-peringatan.index');
    }

    public function create()
    {
        $pelaku_usahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('surat-peringatan.create', compact('pelaku_usahas'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->except('_token');
            $data['created_by'] = auth()->id();
            
            // Resolve pelaku usaha (dari input text biasa)
            $namaPelakuUsaha = $request->input('pelaku_usaha_nama');
            if (!empty($namaPelakuUsaha)) {
                $pu = PelakuUsaha::firstOrCreate(
                    ['nama_perusahaan' => $namaPelakuUsaha],
                    [
                        'alamat' => $data['alamat'] ?? null,
                        'status' => 'aktif',
                        'created_by' => auth()->id()
                    ]
                );
                $data['pelaku_usaha_id'] = $pu->id;
            } else {
                $data['pelaku_usaha_id'] = null;
            }
            unset($data['pelaku_usaha_nama']);
            
            // Format dates
            $dateFields = ['tanggal_penerbitan', 'laporan_1_tgl', 'laporan_2_tgl', 'laporan_3_tgl', 'laporan_4_tgl', 'laporan_5_tgl'];
            foreach ($dateFields as $field) {
                if (empty($data[$field])) {
                    $data[$field] = null;
                }
            }

            SuratPeringatan::create($data);

            DB::commit();
            return redirect()->route('surat-peringatan.index')->with('success', "Data Surat Peringatan berhasil ditambahkan");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambahkan data Surat Peringatan: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        // Tangani link lama (legacy routes) seperti /sp1, /sp2, /sp3
        if (in_array(strtolower($id), ['sp1', 'sp2', 'sp3'])) {
            return redirect()->route('surat-peringatan.index')->with('info', 'Halaman yang Anda tuju telah digabungkan ke menu utama Surat Peringatan.');
        }
        
        // Fitur show detail belum diimplementasikan, kembalikan ke index
        return redirect()->route('surat-peringatan.index');
    }

    public function edit($id)
    {
        $spsatu = SuratPeringatan::findOrFail($id);
        $pelaku_usahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        // pass as $data to view
        return view('surat-peringatan.edit', [
            'spsatu' => $spsatu,
            'data' => $spsatu, 
            'pelaku_usahas' => $pelaku_usahas
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $spsatu = SuratPeringatan::findOrFail($id);
            $data = $request->except('_token', '_method');
            
            // Resolve pelaku usaha (dari input text biasa)
            $namaPelakuUsaha = $request->input('pelaku_usaha_nama');
            if (!empty($namaPelakuUsaha)) {
                $pu = PelakuUsaha::firstOrCreate(
                    ['nama_perusahaan' => $namaPelakuUsaha],
                    [
                        'alamat' => $data['alamat'] ?? null,
                        'status' => 'aktif',
                        'created_by' => auth()->id()
                    ]
                );
                $data['pelaku_usaha_id'] = $pu->id;
            } else {
                $data['pelaku_usaha_id'] = null;
            }
            unset($data['pelaku_usaha_nama']);
            
            // Format dates
            $dateFields = ['tanggal_penerbitan', 'laporan_1_tgl', 'laporan_2_tgl', 'laporan_3_tgl', 'laporan_4_tgl', 'laporan_5_tgl'];
            foreach ($dateFields as $field) {
                if (empty($data[$field])) {
                    $data[$field] = null;
                }
            }

            $spsatu->update($data);

            DB::commit();
            return redirect()->route('surat-peringatan.index')->with('success', "Data Surat Peringatan berhasil diperbarui");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal memperbarui data Surat Peringatan: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $sp = SuratPeringatan::findOrFail($id);
            $sp->delete();
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data']);
        }
    }
}
