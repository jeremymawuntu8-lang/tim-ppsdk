<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyIsActive
{
    /**
     * Handle an incoming request.
     * Cek apakah akun perusahaan (login via Google) sudah melengkapi profil & sudah aktif.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Middleware ini khusus untuk user dengan provider google (perusahaan)
        // Jika admin biasa, biarkan saja lolos (atau lewati)
        if (! $user->isCompany()) {
            return $next($request);
        }

        $company = $user->company;

        // Jika belum isi profil
        if (! $company) {
            return redirect()->route('company.complete-profile');
        }

        // Jika belum aktif, cegah akses upload (arahkan ke dashboard untuk melihat status)
        if (! $company->isActive()) {
            // Kecuali jika request tersebut memang mengarah ke dashboard, profil edit, atau upload success, izinkan
            if ($request->routeIs('company.dashboard') || $request->routeIs('company.profil.*') || $request->routeIs('company.upload.success')) {
                return $next($request);
            }

            return redirect()->route('company.dashboard')
                ->with('error', 'Akun Anda saat ini belum aktif. Tidak dapat mengakses fitur tersebut.');
        }

        return $next($request);
    }
}
