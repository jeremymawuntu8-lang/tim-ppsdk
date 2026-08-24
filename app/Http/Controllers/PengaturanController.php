<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = [
            'nama_aplikasi' => config('app.name'),
            'nama_instansi' => 'Pangkalan PSDKP Bitung',
            'alamat_instansi' => 'Kota Bitung, Sulawesi Utara',
        ];

        return view('pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => ['required', 'string', 'max:255'],
            'nama_instansi' => ['required', 'string', 'max:255'],
            'alamat_instansi' => ['nullable', 'string'],
        ]);

        // Simpan ke tabel settings atau file config sesuai kebutuhan produksi.
        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
