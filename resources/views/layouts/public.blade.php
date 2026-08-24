<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') | TIM IPSDK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .public-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .public-navbar .nav-link {
            color: #475569;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        .public-navbar .nav-link:hover, .public-navbar .nav-link.active {
            color: #0A3D6B;
        }
        .public-footer {
            background: #0A3D6B;
            color: rgba(255,255,255,0.8);
            padding: 3rem 0 1.5rem;
            margin-top: auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="public-navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
            <div>
                <div class="fw-bold text-dark lh-1">TIM IPSDK</div>
                <div class="text-muted" style="font-size: 0.7rem;">Kementerian Kelautan dan Perikanan</div>
            </div>
        </a>

        <div class="d-none d-md-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="#" class="nav-link">Tentang</a>
            <a href="#" class="nav-link">Regulasi</a>
            <a href="#" class="nav-link">Persyaratan</a>
            <a href="#" class="nav-link">Kontak</a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @auth
                @if(auth()->user()->isCompany())
                    <a href="{{ route('company.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                        Dashboard <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                        Dashboard Admin <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            @else
                <a href="{{ route('auth.google') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" style="background: #0A3D6B; border-color: #0A3D6B;">
                    Upload Dokumen <i class="fas fa-cloud-upload-alt ms-1"></i>
                </a>
            @endauth
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="public-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-5">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="50" class="me-3 bg-white p-1 rounded">
                    <div>
                        <h5 class="text-white fw-bold mb-0">TIM IPSDK</h5>
                        <div class="small opacity-75">Kementerian Kelautan dan Perikanan</div>
                    </div>
                </div>
                <p class="small opacity-75 pe-md-4">
                    Sistem Informasi Pengawasan Pengelolaan Ruang Laut. Platform resmi untuk pendaftaran dan pelaporan dokumen bagi pelaku usaha kelautan dan perikanan.
                </p>
            </div>
            <div class="col-md-3">
                <h6 class="text-white fw-bold mb-3">Tautan Cepat</h6>
                <ul class="list-unstyled small opacity-75">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tentang Kami</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Regulasi & Hukum</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Panduan Pengguna</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled small opacity-75">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Medan Merdeka Timur No. 16, Jakarta Pusat</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> admin@ppsdk.go.id</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> (021) 1234567</li>
                </ul>
            </div>
        </div>
        <div class="border-top border-secondary pt-3 text-center small opacity-50">
            &copy; {{ date('Y') }} TIM IPSDK - Kementerian Kelautan dan Perikanan Republik Indonesia.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
