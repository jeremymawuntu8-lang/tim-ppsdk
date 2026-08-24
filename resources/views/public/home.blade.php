@extends('layouts.public')
@section('title', 'Selamat Datang')

@section('content')
{{-- Hero Section --}}
<div class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #0A3D6B 0%, #1565C0 100%); padding: 120px 0;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.05;"></div>
    
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white pe-lg-5">
                <div class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm">
                    Sistem Informasi Terpadu KKP
                </div>
                <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">
                    Pengawasan Pengelolaan <br> <span class="text-warning">Ruang Laut</span>
                </h1>
                <p class="lead mb-5 opacity-75" style="font-weight: 400; font-size: 1.15rem; max-width: 600px;">
                    Portal resmi pelayanan satu pintu bagi pelaku usaha untuk mengelola perizinan, mengunggah dokumen persyaratan, dan memantau status pengawasan secara transparan dan akuntabel.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    @auth
                        <a href="{{ auth()->user()->isCompany() ? route('company.dashboard') : route('dashboard') }}" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="fas fa-arrow-right me-2"></i> Ke Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('auth.google') }}" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow">
                            <i class="fab fa-google me-2"></i> Daftar / Masuk
                        </a>
                    @endauth
                    <a href="#cara-kerja" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-semibold">
                        Pelajari Sistem
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="{{ asset('images/hero-illustration.svg') }}" onerror="this.src='https://illustrations.popsy.co/amber/freelancer.svg'" alt="Ilustrasi" class="img-fluid drop-shadow" style="transform: scale(1.1);">
            </div>
        </div>
    </div>
</div>

{{-- Features Section --}}
<div class="py-5 bg-white" id="cara-kerja">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wide">Pelayanan Kami</h6>
            <h2 class="fw-bold text-dark">Mengapa Menggunakan Portal Ini?</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Kami menghadirkan inovasi digital untuk mempercepat proses birokrasi dan meningkatkan transparansi layanan.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
                    <div class="bg-primary-soft text-primary mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Proses Cepat</h5>
                    <p class="text-muted mb-0">Upload dokumen persyaratan Anda kapan saja dan di mana saja tanpa perlu datang ke kantor fisik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
                    <div class="bg-success-soft text-success mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Aman & Terpusat</h5>
                    <p class="text-muted mb-0">Seluruh data perusahaan dan dokumen legal Anda tersimpan dengan aman dengan enkripsi standar industri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
                    <div class="bg-warning-soft text-warning mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Transparan</h5>
                    <p class="text-muted mb-0">Pantau status verifikasi dan jadwal pengawasan secara real-time langsung melalui dashboard Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Call to Action --}}
<div class="py-5" style="background: #f1f5f9;">
    <div class="container py-5 text-center">
        <h2 class="fw-bold text-dark mb-4">Siap Mengurus Perizinan Anda?</h2>
        <p class="text-muted lead mb-5 mx-auto" style="max-width: 600px;">Masuk menggunakan akun Google perusahaan Anda sekarang. Jika Anda baru pertama kali, Anda akan diarahkan untuk mengisi profil perusahaan.</p>
        
        @auth
            <a href="{{ auth()->user()->isCompany() ? route('company.dashboard') : route('dashboard') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" style="background: #0A3D6B; border-color: #0A3D6B;">
                <i class="fas fa-cloud-upload-alt me-2"></i> LANJUT KE DASHBOARD SAYA
            </a>
        @else
            <a href="{{ route('auth.google') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" style="background: #0A3D6B; border-color: #0A3D6B;">
                <i class="fas fa-cloud-upload-alt me-2"></i> UPLOAD DOKUMEN SEKARANG
            </a>
        @endauth
    </div>
</div>
@endsection