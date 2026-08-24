<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;

class WilayahController extends Controller
{
    public function provinsi()
    {
        $provinsis = Provinsi::withCount('kabupatens')->orderBy('nama')->paginate(15);
        return view('wilayah.provinsi', compact('provinsis'));
    }

    public function kabupaten()
    {
        $kabupatens = Kabupaten::with('provinsi')->orderBy('nama')->paginate(15);
        return view('wilayah.kabupaten', compact('kabupatens'));
    }

    public function kecamatan()
    {
        $kecamatans = Kecamatan::with('kabupaten.provinsi')->orderBy('nama')->paginate(15);
        return view('wilayah.kecamatan', compact('kecamatans'));
    }

    public function kelurahan()
    {
        $kelurahans = Kelurahan::with('kecamatan.kabupaten')->orderBy('nama')->paginate(15);
        return view('wilayah.kelurahan', compact('kelurahans'));
    }

    public function kabupatenByProvinsi(Provinsi $provinsi)
    {
        return response()->json(Kabupaten::where('provinsi_id', $provinsi->id)->orderBy('nama')->get(['id', 'nama']));
    }

    public function kecamatanByKabupaten(Kabupaten $kabupaten)
    {
        return response()->json(Kecamatan::where('kabupaten_id', $kabupaten->id)->orderBy('nama')->get(['id', 'nama']));
    }

    public function kelurahanByKecamatan(Kecamatan $kecamatan)
    {
        return response()->json(Kelurahan::where('kecamatan_id', $kecamatan->id)->orderBy('nama')->get(['id', 'nama']));
    }
}
