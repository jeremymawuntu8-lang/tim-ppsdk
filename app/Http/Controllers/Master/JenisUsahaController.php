<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JenisUsaha;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class JenisUsahaController extends Controller
{
    public function index()
    {
        return view('jenis-usaha.index');
    }

    public function data()
    {
        $query = JenisUsaha::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '<button type="button" class="btn btn-sm btn-warning btn-icon me-1" onclick="editJenisUsaha(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>' .
                       '<button type="button" class="btn btn-sm btn-danger btn-icon" onclick="hapusJenisUsaha(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }



    public function edit(JenisUsaha $jenisUsaha)
    {
        return view('jenis-usaha.edit', compact('jenisUsaha'));
    }

    public function update(Request $request, JenisUsaha $jenisUsaha)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['nullable', 'string', 'max:20', 'unique:jenis_usahas,kode,'.$jenisUsaha->id],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jenisUsaha->update($data);
        ActivityLog::catat('Edit', 'Jenis Usaha', "Mengubah jenis usaha: {$jenisUsaha->nama}");

        return redirect()->route('jenis-usaha.index')->with('success', 'Jenis usaha berhasil diperbarui.');
    }

    public function destroy(JenisUsaha $jenisUsaha)
    {
        ActivityLog::catat('Hapus', 'Jenis Usaha', "Menghapus jenis usaha: {$jenisUsaha->nama}");
        $jenisUsaha->delete();

        return response()->json(['success' => true, 'message' => 'Jenis usaha berhasil dihapus.']);
    }
}
