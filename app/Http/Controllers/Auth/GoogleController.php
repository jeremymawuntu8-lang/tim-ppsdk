<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect pengguna ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah login berhasil.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Cari user berdasarkan google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // Cek apakah email sudah terdaftar sebagai akun lokal (admin)
            $existingLocal = User::where('email', $googleUser->getEmail())
                ->where('auth_provider', 'local')
                ->first();

            if ($existingLocal) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Email ini sudah terdaftar sebagai akun admin. Silakan login dengan email dan password.']);
            }

            // Buat user baru dari Google
            $user = User::create([
                'name'          => $googleUser->getName(),
                'email'         => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'auth_provider' => 'google',
                'password'      => null,
                'is_active'     => true,
                'foto_profil'   => $googleUser->getAvatar(),
            ]);

            // Assign role 'perusahaan'
            $user->assignRole('perusahaan');
        }

        // Cek is_active
        if (! $user->is_active) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        // Jika belum melengkapi profil perusahaan
        if (! $user->company) {
            return redirect()->route('company.complete-profile');
        }

        return redirect()->route('company.dashboard');
    }
}
