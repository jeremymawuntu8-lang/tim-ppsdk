<?php

use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiPerusahaanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Master\JenisUsahaController;
use App\Http\Controllers\Master\PelakuUsahaController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\Pengawasan\BaWasAlseController;
use App\Http\Controllers\Pengawasan\BaWasPrlController;
use App\Http\Controllers\Pengawasan\BaReklamasiController;
use App\Http\Controllers\Pengawasan\BaPpkController;
use App\Http\Controllers\Pengawasan\BaPencemaranController;
use App\Http\Controllers\Pengawasan\JadwalPengawasanController;
use App\Http\Controllers\Pengawasan\MonitoringController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\Perusahaan\CompanyDashboardController;
use App\Http\Controllers\Perusahaan\CompanyProfileController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIK AREA
// ==========================================
Route::get('/', [PublicController::class, 'home'])->name('home');

// ==========================================
// AUTHENTICATION (Google & Local)
// ==========================================
Route::middleware('guest')->group(function () {
    // Admin Local Auth
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Google OAuth
    Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'callback']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// ==========================================
// PERUSAHAAN PORTAL
// ==========================================
Route::middleware(['auth'])->prefix('company')->name('company.')->group(function () {
    
    // Form upload dokumen (dulu lengkapi profil)
    Route::get('complete-profile', [CompanyProfileController::class, 'create'])->name('complete-profile');
    Route::post('complete-profile', [CompanyProfileController::class, 'store'])->name('complete-profile.store');
    
    // Halaman Sukses
    Route::get('upload-success', [CompanyProfileController::class, 'success'])->name('upload.success');
    
    // Dashboard Perusahaan
    Route::get('dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
    
    // Profil Edit & Update (Boleh akses edit profil meskipun status masih PENDING/REJECTED asalkan sudah ada Company record)
    Route::get('profil/edit', [CompanyProfileController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [CompanyProfileController::class, 'update'])->name('profil.update');

    // Riwayat Pengajuan (boleh dilihat di status APAPUN, selama profil sudah pernah diisi)
    Route::get('riwayat', [CompanyDashboardController::class, 'riwayat'])->name('riwayat');

    // Fitur Utama Perusahaan (Harus sudah ACTIVE)
    Route::middleware('company.active')->group(function () {
        // Karena sistem yang lama blm punya upload spesifik dr perusahaan, kita arahkan ke halaman dummy sementara, atau nnti bisa diintegrasikan dg tabel dokumen
        Route::get('upload', function() {
            return view('company.upload');
        })->name('upload');
    });
});

// ==========================================
// ADMIN & INTERNAL PORTAL
// ==========================================
Route::middleware(['auth', 'active', 'internal'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data (kelola-master-data)
    Route::middleware('permission:kelola-master-data')->group(function () {
        Route::get('pelaku-usaha/data', [PelakuUsahaController::class, 'data'])->name('pelaku-usaha.data');
        Route::get('pelaku-usaha/export-excel', [PelakuUsahaController::class, 'exportExcel'])->name('pelaku-usaha.export-excel');
        Route::get('pelaku-usaha/export-pdf', [PelakuUsahaController::class, 'exportPdf'])->name('pelaku-usaha.export-pdf');
        Route::get('pelaku-usaha/dokumen/{dokumenId}/download', [PelakuUsahaController::class, 'downloadDokumen'])->name('pelaku-usaha.dokumen.download');
        Route::resource('pelaku-usaha', PelakuUsahaController::class)->except(['create', 'store']);

        Route::get('jenis-usaha/data', [JenisUsahaController::class, 'data'])->name('jenis-usaha.data');
        Route::resource('jenis-usaha', JenisUsahaController::class)->except(['show', 'create', 'store']);

        Route::prefix('wilayah')->name('wilayah.')->group(function () {
            Route::get('provinsi', [WilayahController::class, 'provinsi'])->name('provinsi');
            Route::get('kabupaten', [WilayahController::class, 'kabupaten'])->name('kabupaten');
            Route::get('kecamatan', [WilayahController::class, 'kecamatan'])->name('kecamatan');
            Route::get('kelurahan', [WilayahController::class, 'kelurahan'])->name('kelurahan');
        });
    });

    // Endpoint wilayah cascading dibiarkan bisa diakses semua user login (dipakai form pengawasan & pelaku usaha)
    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        Route::get('kabupaten-by-provinsi/{provinsi}', [WilayahController::class, 'kabupatenByProvinsi'])->name('kabupaten-by-provinsi');
        Route::get('kecamatan-by-kabupaten/{kabupaten}', [WilayahController::class, 'kecamatanByKabupaten'])->name('kecamatan-by-kabupaten');
        Route::get('kelurahan-by-kecamatan/{kecamatan}', [WilayahController::class, 'kelurahanByKecamatan'])->name('kelurahan-by-kecamatan');
    });

    // Pengawasan (kelola-pengawasan)
    Route::middleware('permission:kelola-pengawasan')->group(function () {
        Route::get('ba-was-prl/data', [BaWasPrlController::class, 'data'])->name('ba-was-prl.data');
        Route::get('ba-was-prl/{ba_was_prl}/cetak', [BaWasPrlController::class, 'cetak'])->name('ba-was-prl.cetak');
        Route::resource('ba-was-prl', BaWasPrlController::class);

        Route::get('ba-was-alse/data', [BaWasAlseController::class, 'data'])->name('ba-was-alse.data');
        Route::get('ba-was-alse/{ba_was_alse}/cetak', [BaWasAlseController::class, 'cetak'])->name('ba-was-alse.cetak');
        Route::resource('ba-was-alse', BaWasAlseController::class);

        Route::get('ba-reklamasi/data', [BaReklamasiController::class, 'data'])->name('ba-reklamasi.data');
        Route::get('ba-reklamasi/{ba_reklamasi}/cetak', [BaReklamasiController::class, 'cetak'])->name('ba-reklamasi.cetak');
        Route::resource('ba-reklamasi', BaReklamasiController::class);

        Route::get('ba-ppk/data', [BaPpkController::class, 'data'])->name('ba-ppk.data');
        Route::get('ba-ppk/{ba_ppk}/cetak', [BaPpkController::class, 'cetak'])->name('ba-ppk.cetak');
        Route::resource('ba-ppk', BaPpkController::class);

        Route::get('ba-pencemaran/data', [BaPencemaranController::class, 'data'])->name('ba-pencemaran.data');
        Route::get('ba-pencemaran/{ba_pencemaran}/cetak', [BaPencemaranController::class, 'cetak'])->name('ba-pencemaran.cetak');
        Route::resource('ba-pencemaran', BaPencemaranController::class);

        Route::get('jadwal/data', [JadwalPengawasanController::class, 'data'])->name('jadwal.data');
        Route::resource('jadwal', JadwalPengawasanController::class)->except(['create', 'show']);

        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });

    // Dokumen (kelola-dokumen)
    Route::middleware('permission:kelola-dokumen')->group(function () {
        Route::get('dokumen/data', [DokumenController::class, 'data'])->name('dokumen.data');
        Route::get('dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');
        Route::get('dokumen/company/{company}/download', [DokumenController::class, 'downloadCompany'])->name('dokumen.company.download');
        Route::delete('dokumen/company/{company}', [DokumenController::class, 'destroyCompany'])->name('dokumen.company.destroy');
        Route::resource('dokumen', DokumenController::class)->except(['show', 'create', 'edit', 'update'])->parameters(['dokumen' => 'dokumen']);
    });

    // Map (lihat-map)
    Route::middleware('permission:lihat-map')->group(function () {
        Route::get('map', [MapController::class, 'index'])->name('map.index');
        Route::get('map/data', [MapController::class, 'data'])->name('map.data');
    });

    // Laporan (lihat-laporan)
    Route::middleware('permission:lihat-laporan')->group(function () {
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        Route::post('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    });

    // User Management (kelola-user)
    Route::middleware('permission:kelola-user')->group(function () {
        Route::get('users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('users', UserController::class)->except(['show']);
        
        // Verifikasi Perusahaan Admin
        Route::prefix('admin/verifikasi-perusahaan')->name('admin.verifikasi-perusahaan.')->group(function () {
            Route::get('/', [VerifikasiPerusahaanController::class, 'index'])->name('index');
            Route::get('/{company}', [VerifikasiPerusahaanController::class, 'show'])->name('show');
            Route::post('/{company}/approve', [VerifikasiPerusahaanController::class, 'approve'])->name('approve');
            Route::post('/{company}/revision', [VerifikasiPerusahaanController::class, 'revision'])->name('revision');
            Route::post('/{company}/reject', [VerifikasiPerusahaanController::class, 'reject'])->name('reject');
        });
    });

    // Log Aktivitas (lihat-log)
    Route::middleware('permission:lihat-log')->group(function () {
        Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
        Route::get('log-aktivitas/data', [LogAktivitasController::class, 'data'])->name('log-aktivitas.data');
    });

    // Pengaturan (kelola-pengaturan)
    Route::middleware('permission:kelola-pengaturan')->group(function () {
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // Profil - semua user login boleh akses profil sendiri
    Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});