<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengawasan;

class MonitoringController extends Controller
{
    public function index()
    {
        $total = JadwalPengawasan::count();
        $belum = JadwalPengawasan::where('status', 'belum_dilaksanakan')->count();
        $berjalan = JadwalPengawasan::where('status', 'sedang_berjalan')->count();
        $selesai = JadwalPengawasan::where('status', 'selesai')->count();

        $timeline = JadwalPengawasan::with('pelakuUsaha')->orderByDesc('tanggal_rencana')->limit(15)->get();

        $grafikBulanan = collect(range(0, 5))->map(function ($i) {
            $bulan = now()->subMonths(5 - $i);
            return [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => JadwalPengawasan::whereYear('tanggal_rencana', $bulan->year)->whereMonth('tanggal_rencana', $bulan->month)->count(),
            ];
        });

        return view('monitoring.index', compact('total', 'belum', 'berjalan', 'selesai', 'timeline', 'grafikBulanan'));
    }
}
