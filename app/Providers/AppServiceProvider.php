<?php

namespace App\Providers;

use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\BaReklamasi;
use App\Models\BaPpk;
use App\Models\BaPencemaran;
use App\Models\PelakuUsaha;
use App\Models\User;
use App\Policies\BaWasAlsePolicy;
use App\Policies\BaWasPrlPolicy;
use App\Policies\PelakuUsahaPolicy;
use App\Policies\UserPolicy;
use App\Observers\BaMasterDataSyncObserver;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(PelakuUsaha::class, PelakuUsahaPolicy::class);
        Gate::policy(BaWasPrl::class, BaWasPrlPolicy::class);
        Gate::policy(BaWasAlse::class, BaWasAlsePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        BaWasPrl::observe(BaMasterDataSyncObserver::class);
        BaWasAlse::observe(BaMasterDataSyncObserver::class);
        BaReklamasi::observe(BaMasterDataSyncObserver::class);
        BaPpk::observe(BaMasterDataSyncObserver::class);
        BaPencemaran::observe(BaMasterDataSyncObserver::class);

        // Secara default, middleware 'guest' (dipakai di /login & /auth/google) akan
        // melempar user yang TERNYATA sudah login ke route pertama yang bernama
        // 'dashboard' yang ia temukan. Di project ini ada 2 route bernama mirip
        // ('dashboard' milik admin & 'company.dashboard' milik perusahaan), jadi
        // perilaku bawaan itu bisa salah arah. Kita timpa agar redirect-nya sadar role.
        RedirectIfAuthenticated::redirectUsing(function (Request $request) {
            $user = $request->user();

            return $user?->isCompany()
                ? route('company.dashboard')
                : route('dashboard');
        });
    }
}