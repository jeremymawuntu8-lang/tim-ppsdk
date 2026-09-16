<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\PelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalPengawasanController extends Controller
{
    public function index(Request $request)
    {
        $prl = DB::table('ba_was_prls')
            ->select('id', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', 'tim_pengawas', 'status', DB::raw("'BA WAS PRL' as jenis_pengawasan"));
            
        $alse = DB::table('ba_was_alses')
            ->select('id', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', 'tim_pengawas', 'status', DB::raw("'BA WAS ALSE' as jenis_pengawasan"));
            
        $reklamasi = DB::table('ba_reklamasis')
            ->select('id', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', DB::raw("CONCAT_WS(', ', ttd_pengawas_1, ttd_pengawas_2) as tim_pengawas"), 'status', DB::raw("'BA Reklamasi' as jenis_pengawasan"));
            
        $ppk = DB::table('ba_ppks')
            ->select('id', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', 'ttd_pengawas_1 as tim_pengawas', 'status', DB::raw("'BA PPK' as jenis_pengawasan"));
            
        $pencemaran = DB::table('ba_pencemarans')
            ->select('id', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', 'ttd_pengawas_1 as tim_pengawas', 'status', DB::raw("'BA Pencemaran' as jenis_pengawasan"));

        $query = $prl->union($alse)->union($reklamasi)->union($ppk)->union($pencemaran);
        
        $results = DB::table(DB::raw("({$query->toSql()}) as merged_ba"))
            ->mergeBindings($query)
            ->join('pelaku_usahas', 'merged_ba.pelaku_usaha_id', '=', 'pelaku_usahas.id')
            ->select('merged_ba.*', 'pelaku_usahas.nama_perusahaan', 'pelaku_usahas.nomor_hp')
            ->orderBy('merged_ba.tanggal_pengawasan', 'desc')
            ->get();

        $jadwals = $results->map(function($item) {
            $item->url = '#';
            switch ($item->jenis_pengawasan) {
                case 'BA WAS PRL': $item->url = route('ba-was-prl.show', $item->id); break;
                case 'BA WAS ALSE': $item->url = route('ba-was-alse.show', $item->id); break;
                case 'BA Reklamasi': $item->url = route('ba-reklamasi.show', $item->id); break;
                case 'BA PPK': $item->url = route('ba-ppk.show', $item->id); break;
                case 'BA Pencemaran': $item->url = route('ba-pencemaran.show', $item->id); break;
            }
            return $item;
        });

        return view('jadwal.index', compact('jadwals'));
    }
}
