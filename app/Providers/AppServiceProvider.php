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
use App\Observers\BaTimelineSyncObserver;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Format pesan max validation untuk file agar menampilkan ukuran MB yang ramah
        Validator::replacer('max', function ($message, $attribute, $rule, $parameters) {
            if (isset($parameters[0]) && is_numeric($parameters[0]) && (int)$parameters[0] >= 1024) {
                $mb = round((int)$parameters[0] / 1024);
                return str_replace([':max kilobytes', ':max'], ["{$mb} MB", "{$mb} MB"], $message);
            }
            return str_replace(':max', $parameters[0] ?? '', $message);
        });

        Paginator::useBootstrapFive();
        Gate::policy(PelakuUsaha::class, PelakuUsahaPolicy::class);
        Gate::policy(BaWasPrl::class, BaWasPrlPolicy::class);
        Gate::policy(BaWasAlse::class, BaWasAlsePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        BaWasPrl::observe(BaMasterDataSyncObserver::class);
        BaWasAlse::observe(BaMasterDataSyncObserver::class);
        BaReklamasi::observe(BaMasterDataSyncObserver::class);
        BaPpk::observe(BaMasterDataSyncObserver::class);
        BaPencemaran::observe(BaMasterDataSyncObserver::class);

        BaWasPrl::observe(BaTimelineSyncObserver::class);
        BaWasAlse::observe(BaTimelineSyncObserver::class);
        BaReklamasi::observe(BaTimelineSyncObserver::class);
        BaPpk::observe(BaTimelineSyncObserver::class);
        BaPencemaran::observe(BaTimelineSyncObserver::class);

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