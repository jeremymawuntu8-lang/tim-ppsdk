<nav class="app-header navbar navbar-expand bg-body border-bottom-0">
    <div class="container-fluid">
        {{-- Left: Hamburger + Brand --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link px-2" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars" style="font-size: 1.1rem;"></i>
                </a>
            </li>
            <li class="nav-item d-none d-lg-block ms-2">
                <span class="navbar-brand-text d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 26px;" class="me-2">
                    Sistem Informasi Pengawasan SDK
                </span>
            </li>
            <li class="nav-item d-lg-none ms-1">
                <span class="navbar-brand-text d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 24px;" class="me-1">
                    <span class="fw-bold" style="font-size: 0.85rem; color: var(--ppsdk-primary);">TIM IPSDK</span>
                </span>
            </li>
        </ul>

        {{-- Right: Notifications + User --}}
        <ul class="navbar-nav ms-auto align-items-center">
            {{-- Notification --}}
            <li class="nav-item dropdown">
                <a class="nav-link position-relative px-2" data-bs-toggle="dropdown" href="#">
                    <i class="fas fa-bell" style="font-size: 1rem;"></i>
                    @php
                        $jadwalCount = \App\Models\JadwalPengawasan::where('status', 'belum_dilaksanakan')
                            ->where('tanggal_rencana', '>=', now()->startOfWeek())
                            ->where('tanggal_rencana', '<=', now()->endOfWeek())
                            ->count();
                    @endphp
                    @if($jadwalCount > 0)
                        <span class="badge bg-danger navbar-badge">{{ $jadwalCount }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                    <span class="dropdown-item-text fw-bold text-dark" style="font-size: 0.88rem;">
                        <i class="fas fa-bell me-1 text-primary"></i> Notifikasi
                    </span>
                    <div class="dropdown-divider my-1"></div>
                    @if($jadwalCount > 0)
                        <a href="{{ route('jadwal.index') }}" class="dropdown-item py-2">
                            <i class="fas fa-calendar-alt me-2 text-warning"></i>
                            <span>{{ $jadwalCount }} jadwal pengawasan minggu ini</span>
                        </a>
                    @else
                        <div class="dropdown-item-text text-muted py-2 text-center" style="font-size: 0.82rem;">
                            <i class="fas fa-check-circle me-1"></i> Tidak ada notifikasi baru
                        </div>
                    @endif
                </div>
            </li>

            {{-- User Dropdown --}}
            <li class="nav-item dropdown ms-1">
                <a class="nav-link user-dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" href="#">
                    <img src="{{ auth()->user()->foto_profil ? asset('storage/'.auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=0A3D6B&color=fff&size=36' }}"
                         class="user-avatar me-2" alt="">
                    <span class="d-none d-md-inline fw-semibold" style="font-size: 0.84rem; color: var(--ppsdk-text);">
                        {{ Str::limit(auth()->user()->name, 18) }}
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem; opacity: 0.5;"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-2">
                    <div class="px-3 py-2 border-bottom mb-1">
                        <div class="fw-bold" style="font-size: 0.85rem;">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                    </div>
                    <a href="{{ route('profil.index') }}" class="dropdown-item">
                        <i class="fas fa-user me-2 text-muted"></i> Profil Saya
                    </a>
                    @can('kelola-pengaturan')
                    <a href="{{ route('pengaturan.index') }}" class="dropdown-item">
                        <i class="fas fa-cog me-2 text-muted"></i> Pengaturan
                    </a>
                    @endcan
                    <div class="dropdown-divider my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
