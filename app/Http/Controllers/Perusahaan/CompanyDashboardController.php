<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\DokumenPelakuUsaha;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $company = $user->company;

        // Jika belum punya profil, paksa lengkapi dulu
        if (! $company) {
            return redirect()->route('company.complete-profile');
        }

        $data = compact('user', 'company');

        // Jika aktif, tambahkan statistik dokumen
        if ($company->isActive()) {
            $data['totalDokumen'] = DokumenPelakuUsaha::where('uploaded_by', $user->id)->count();
        }

        return view('company.dashboard', $data);
    }

    /**
     * Riwayat pengajuan/upload perusahaan.
     * Sengaja TIDAK digembok oleh status company (aktif/revisi/pending/ditolak) karena
     * riwayat pengajuan harus tetap bisa dilihat kapan pun, termasuk justru saat
     * perusahaan sedang diminta revisi (agar mereka bisa melihat ulang apa yang
     * sudah pernah dikirim).
     */
    public function riwayat()
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.complete-profile');
        }

        return view('company.riwayat', compact('company'));
    }
}