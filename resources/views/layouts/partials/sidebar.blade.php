<aside class="app-sidebar shadow-lg" data-bs-theme="dark" id="appSidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image me-2">
            <span class="brand-text">TIM IPSDK</span>
        </a>
    </div>

    {{-- Close button for mobile --}}
    <button type="button" class="btn-close btn-close-white d-lg-none position-absolute" style="top: 1.25rem; right: 1rem; z-index: 10; opacity: 0.6;" id="sidebarClose"></button>

    <div class="sidebar-wrapper" style="overflow-y: auto; height: calc(100vh - 75px);">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gauge-high"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Master Data --}}
                @can('kelola-master-data')
                <li class="nav-item {{ request()->routeIs(['pelaku-usaha.*','jenis-usaha.*','wilayah.*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Master Data <i class="nav-arrow fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pelaku-usaha.index') }}" class="nav-link {{ request()->routeIs('pelaku-usaha.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Pelaku Usaha</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jenis-usaha.index') }}" class="nav-link {{ request()->routeIs('jenis-usaha.*') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Jenis Usaha</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('wilayah.provinsi') }}" class="nav-link {{ request()->routeIs('wilayah.*') ? 'active' : '' }}">
                                <i class="fas fa-map-location-dot nav-icon"></i>
                                <p>Wilayah</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Pengawasan --}}
                @can('kelola-pengawasan')
                <li class="nav-item {{ request()->routeIs(['ba-was-prl.*','ba-was-alse.*','ba-reklamasi.*','ba-ppk.*','ba-pencemaran.*','jadwal.*','monitoring.*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-magnifying-glass"></i>
                        <p>Pengawasan <i class="nav-arrow fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ba-was-prl.index') }}" class="nav-link {{ request()->routeIs('ba-was-prl.*') ? 'active' : '' }}">
                                <i class="fas fa-file-lines nav-icon"></i>
                                <p>BA WAS PRL</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ba-was-alse.index') }}" class="nav-link {{ request()->routeIs('ba-was-alse.*') ? 'active' : '' }}">
                                <i class="fas fa-file-lines nav-icon"></i>
                                <p>BA WAS ALSE</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ba-reklamasi.index') }}" class="nav-link {{ request()->routeIs('ba-reklamasi.*') ? 'active' : '' }}">
                                <i class="fas fa-file-lines nav-icon"></i>
                                <p>BA Reklamasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ba-ppk.index') }}" class="nav-link {{ request()->routeIs('ba-ppk.*') ? 'active' : '' }}">
                                <i class="fas fa-file-lines nav-icon"></i>
                                <p>BA PPK</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ba-pencemaran.index') }}" class="nav-link {{ request()->routeIs('ba-pencemaran.*') ? 'active' : '' }}">
                                <i class="fas fa-file-lines nav-icon"></i>
                                <p>BA Pencemaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-days nav-icon"></i>
                                <p>Jadwal Pengawasan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('monitoring.index') }}" class="nav-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Monitoring</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Dokumen Menu --}}
                @canany(['kelola-dokumen', 'kelola-user'])
                <li class="nav-item {{ request()->routeIs(['dokumen.*', 'admin.verifikasi-perusahaan.*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>Dokumen <i class="nav-arrow fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('kelola-user')
                        <li class="nav-item">
                            <a href="{{ route('admin.verifikasi-perusahaan.index') }}" class="nav-link {{ request()->routeIs('admin.verifikasi-perusahaan.*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Pengajuan Dokumen</p>
                            </a>
                        </li>
                        @endcan
                        @can('kelola-dokumen')
                        <li class="nav-item">
                            <a href="{{ route('dokumen.index') }}" class="nav-link {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p>Dokumen Pelaku Usaha</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @can('lihat-map')
                <li class="nav-item">
                    <a href="{{ route('map.index') }}" class="nav-link {{ request()->routeIs('map.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map"></i>
                        <p>Map</p>
                    </a>
                </li>
                @endcan

                @can('lihat-laporan')
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                @endcan

                @can('kelola-user')
                <li class="nav-item {{ request()->routeIs(['users.*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-gear"></i>
                        <p>User Management <i class="nav-arrow fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield nav-icon"></i>
                                <p>Data Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                @can('lihat-log')
                <li class="nav-item">
                    <a href="{{ route('log-aktivitas.index') }}" class="nav-link {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock-rotate-left"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>
                @endcan

                {{-- Separator --}}
                <li class="nav-header">Lainnya</li>

                @can('kelola-pengaturan')
                <li class="nav-item">
                    <a href="{{ route('pengaturan.index') }}" class="nav-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gear"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('profil.index') }}" class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil</p>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Sidebar User Info --}}
        <div class="sidebar-user-panel d-flex align-items-center mt-auto">
            <img src="{{ auth()->user()->foto_profil ? asset('storage/'.auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1565C0&color=fff&size=36' }}"
                 class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" alt="">
            <div class="flex-grow-1 overflow-hidden">
                <div class="text-white fw-semibold text-truncate" style="font-size: 0.82rem;">{{ auth()->user()->name }}</div>
                <div class="text-white-50" style="font-size: 0.72rem;">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.getElementById('sidebarClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                document.body.classList.remove('sidebar-open');
            });
        }
    });
</script>
