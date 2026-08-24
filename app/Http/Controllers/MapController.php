<?php

namespace App\Http\Controllers;

use App\Models\JenisUsaha;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::orderBy('nama')->get();
        $jenisUsahas = JenisUsaha::orderBy('nama')->get();

        return view('map.index', compact('provinsis', 'jenisUsahas'));
    }

    public function data(Request $request)
    {
        $pelakuUsahas = PelakuUsaha::with(['jenisUsaha', 'provinsi', 'kabupaten'])
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->filter($request->all())
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nama_perusahaan' => $p->nama_perusahaan,
                'nomor_pkkprl' => $p->nomor_pkkprl,
                'jenis_usaha' => $p->jenisUsaha->nama ?? '-',
                'luas_pkkprl' => $p->luas_pkkprl,
                'alamat' => $p->alamat,
                'foto_lokasi' => $p->foto_lokasi ? asset('storage/'.$p->foto_lokasi) : null,
                'status' => $p->status,
                'latitude' => $p->latitude,
                'longitude' => $p->longitude,
                'detail_url' => route('pelaku-usaha.show', $p->id),
            ]);

        return response()->json($pelakuUsahas);
    }
}
