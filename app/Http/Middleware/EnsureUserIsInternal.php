<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsInternal
{
    /**
     * Pastikan area admin/internal hanya bisa diakses oleh user internal
     * (super-admin, admin, pengawas, operator, viewer), BUKAN oleh akun
     * perusahaan (login via Google).
     *
     * Tanpa middleware ini, akun perusahaan yang lolos middleware 'auth' + 'active'
     * bisa saja "tersasar" (mis. lewat redirect guest yang salah arah) ke /dashboard
     * milik admin dan melihat data lintas-perusahaan yang bukan haknya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isCompany()) {
            return redirect()->route('company.dashboard');
        }

        return $next($request);
    }
}