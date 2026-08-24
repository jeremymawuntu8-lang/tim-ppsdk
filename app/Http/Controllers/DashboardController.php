<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\JenisUsaha;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelakuUsaha = PelakuUsaha::count();
        $totalBaWasPrl = BaWasPrl::count();
        $totalBaWasAlse = BaWasAlse::count();
        $totalDokumen = \App\Models\PelakuUsahaDokumen::count() + \App\Models\DokumenPelakuUsaha::count();

        $grafikPengawasanBulanan = collect(range(0, 5))->map(function ($i) {
            $bulan = now()->subMonths(5 - $i);
            return [
                'label' => $bulan->translatedFormat('M Y'),
                'prl' => BaWasPrl::whereYear('tanggal_pengawasan', $bulan->year)->whereMonth('tanggal_pengawasan', $bulan->month)->count(),
                'alse' => BaWasAlse::whereYear('tanggal_pengawasan', $bulan->year)->whereMonth('tanggal_pengawasan', $bulan->month)->count(),
            ];
        });

        $grafikJenisUsaha = JenisUsaha::withCount('pelakuUsahas')->having('pelaku_usahas_count', '>', 0)->get();

        $grafikProvinsi = Provinsi::withCount('pelakuUsahas')->having('pelaku_usahas_count', '>', 0)->orderByDesc('pelaku_usahas_count')->limit(8)->get();

        $aktivitasTerbaru = ActivityLog::with('user')->latest()->limit(8)->get();

        $statistikStatus = PelakuUsaha::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');

        $pelakuUsahaMap = PelakuUsaha::whereNotNull('latitude')->whereNotNull('longitude')
            ->select('id', 'nama_perusahaan', 'latitude', 'longitude', 'status')
            ->limit(500)->get();

        $statistikPengajuan = \App\Models\Company::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.index', compact(
            'totalPelakuUsaha', 'totalBaWasPrl', 'totalBaWasAlse', 'totalDokumen',
            'grafikPengawasanBulanan', 'grafikJenisUsaha', 'grafikProvinsi',
            'aktivitasTerbaru', 'statistikStatus', 'pelakuUsahaMap', 'statistikPengajuan'
        ));
    }
}
