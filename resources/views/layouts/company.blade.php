<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Perusahaan') | TIM IPSDK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .company-sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0A3D6B 0%, #1565C0 100%);
            position: fixed;
            left: 0; top: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        .company-sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .company-sidebar .brand img { max-height: 50px; }
        .company-sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-radius: 0;
        }
        .company-sidebar .nav-link:hover,
        .company-sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .company-sidebar .nav-link i { width: 20px; text-align: center; }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .content-area { padding: 1.5rem; flex: 1; }
        @media (max-width: 768px) {
            .company-sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .company-sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- Sidebar Perusahaan --}}
<div class="company-sidebar" id="companySidebar">
    <div class="brand text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-2">
        <div class="text-white fw-bold small">Portal Perusahaan</div>
    </div>

    @php $company = auth()->user()?->company; @endphp

    <nav class="py-3 flex-grow-1">
        <div class="px-3 mb-2">
            <span class="text-white-50 text-xs fw-bold text-uppercase" style="font-size:0.7rem;">Menu</span>
        </div>
        <a href="{{ route('company.dashboard') }}" class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        {{-- Riwayat pengajuan harus tetap terlihat di status apa pun (pending/revisi/aktif/ditolak) --}}
        @if($company)
            <a href="{{ route('company.riwayat') }}" class="nav-link {{ request()->routeIs('company.riwayat') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Riwayat Pengajuan
            </a>
        @endif
        {{-- Upload dokumen lanjutan memang khusus perusahaan yang sudah terverifikasi aktif --}}
        @if($company?->isActive())
            <a href="{{ route('company.upload') }}" class="nav-link {{ request()->routeIs('company.upload') ? 'active' : '' }}">
                <i class="fas fa-upload"></i> Upload Dokumen
            </a>
        @endif
        <a href="{{ route('company.profil.edit') }}" class="nav-link {{ request()->routeIs('company.profil.edit') ? 'active' : '' }}">
            <i class="fas fa-building"></i> Profil Perusahaan
        </a>
    </nav>

    <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
        <div class="d-flex align-items-center gap-2 mb-3">
            <img src="{{ auth()->user()->foto_profil ?: 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1565C0&color=fff' }}"
                 class="rounded-circle" width="36" height="36" style="object-fit: cover;">
            <div>
                <div class="text-white fw-semibold small">{{ auth()->user()->name }}</div>
                <div class="text-white-50" style="font-size: 0.7rem;">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-1"></i> Keluar
            </button>
        </form>
    </div>
</div>

{{-- Konten Utama --}}
<div class="main-content">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('companySidebar').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <h6 class="mb-0 fw-bold text-dark">@yield('page-title', 'Portal Perusahaan')</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($company)
                @php
                    $statusMeta = [
                        'pending'  => ['color' => 'warning', 'icon' => 'clock',        'label' => 'Menunggu Verifikasi'],
                        'revision' => ['color' => 'warning', 'icon' => 'pencil-alt',   'label' => 'Perlu Direvisi'],
                        'active'   => ['color' => 'success', 'icon' => 'check-circle', 'label' => 'Aktif'],
                        'rejected' => ['color' => 'danger',  'icon' => 'times-circle', 'label' => 'Ditolak'],
                    ];
                    $sm = $statusMeta[$company->status] ?? ['color' => 'secondary', 'icon' => 'circle', 'label' => ucfirst($company->status)];
                @endphp
                <span class="badge bg-{{ $sm['color'] }}-soft text-{{ $sm['color'] }} px-3 py-2" style="border-radius: 20px;">
                    <i class="fas fa-{{ $sm['icon'] }} me-1"></i>
                    {{ $sm['label'] }}
                </span>
            @endif
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@stack('scripts')
</body>
</html>