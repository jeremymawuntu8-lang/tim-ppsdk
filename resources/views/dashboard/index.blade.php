@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Welcome Banner --}}
<div class="welcome-banner fade-in">
    <h4><i class="fas fa-hand-sparkles me-2"></i>Selamat Datang, {{ auth()->user()->name }}!</h4>
    <p>{{ now()->translatedFormat('l, d F Y') }} — Sistem Informasi Pengawasan Sumber Daya Kelautan</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-blue fade-in">
            <i class="fas fa-building stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($totalPelakuUsaha) }}</div>
                <div class="stat-label">Pelaku Usaha</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('pelaku-usaha.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-cyan fade-in">
            <i class="fas fa-file-lines stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($totalBaWasPrl) }}</div>
                <div class="stat-label">BA WAS PRL</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('ba-was-prl.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-amber fade-in">
            <i class="fas fa-file-lines stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($totalBaWasAlse) }}</div>
                <div class="stat-label">BA WAS ALSE</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('ba-was-alse.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-green fade-in">
            <i class="fas fa-folder-open stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($totalDokumen) }}</div>
                <div class="stat-label">Dokumen</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('dokumen.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

{{-- Status Pengajuan Dokumen Perusahaan --}}
@if(isset($statistikPengajuan))
<h5 class="fw-bold text-dark mb-3 mt-4"><i class="fas fa-building text-primary me-2"></i>Status Pengajuan Dokumen Perusahaan</h5>
<div class="row g-3 mb-4">
    @php
        $pengajuanTypes = [
            'pending' => ['label' => 'Menunggu Verifikasi', 'color' => 'warning', 'icon' => 'fa-clock'],
            'revision' => ['label' => 'Perlu Revisi', 'color' => 'warning', 'icon' => 'fa-pencil-alt'],
            'active' => ['label' => 'Disetujui', 'color' => 'success', 'icon' => 'fa-check-circle'],
            'rejected' => ['label' => 'Ditolak', 'color' => 'danger', 'icon' => 'fa-times-circle']
        ];
    @endphp
    @foreach($pengajuanTypes as $key => $type)
    <div class="col-xl-3 col-md-6 col-6">
        <a href="{{ route('admin.verifikasi-perusahaan.index', ['status' => $key]) }}" class="text-decoration-none">
            <div class="card border-0 fade-in shadow-sm h-100 hover-elevate">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; background: var(--ppsdk-{{ $type['color'] }}-soft, #f8f9fa); border: 1px solid var(--ppsdk-{{ $type['color'] }}, #dee2e6);">
                        <i class="fas {{ $type['icon'] }}" style="color: var(--ppsdk-{{ $type['color'] }}, #6c757d); font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 1.25rem; line-height: 1.2;">{{ $statistikPengajuan[$key] ?? 0 }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">{{ $type['label'] }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
<style>
.hover-elevate { transition: transform 0.2s, box-shadow 0.2s; }
.hover-elevate:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endif

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-chart-area me-2 text-primary"></i>Grafik Pengawasan (6 Bulan)</h3>
            </div>
            <div class="card-body">
                <div class="chart-container"><canvas id="chartPengawasan"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie me-2 text-primary"></i>Jenis Usaha</h3>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="chart-container" style="max-height: 280px;"><canvas id="chartJenisUsaha"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- Map --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Peta Persebaran Pelaku Usaha</h3>
            </div>
            <div class="card-body p-0">
                <div id="dashboardMap" class="map-container" style="height: 380px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Bottom Row: Province Chart + Activity --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Sebaran per Provinsi (Top 8)</h3>
            </div>
            <div class="card-body">
                <div class="chart-container"><canvas id="chartProvinsi"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock me-2 text-primary"></i>Aktivitas Terbaru</h3>
            </div>
            <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
                @forelse($aktivitasTerbaru as $log)
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-content">
                            <strong>{{ $log->user->name ?? 'Sistem' }}</strong>
                            {{ strtolower($log->aktivitas) }} data {{ $log->modul }}
                            <div class="activity-time">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-4">
                        <i class="fas fa-history empty-state-icon"></i>
                        <div class="empty-state-title">Belum ada aktivitas</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart: Pengawasan Bulanan
    const ctxPengawasan = document.getElementById('chartPengawasan');
    new Chart(ctxPengawasan, {
        type: 'line',
        data: {
            labels: @json($grafikPengawasanBulanan->pluck('label')),
            datasets: [
                {
                    label: 'BA WAS PRL',
                    data: @json($grafikPengawasanBulanan->pluck('prl')),
                    borderColor: '#0A3D6B',
                    backgroundColor: 'rgba(10, 61, 107, 0.08)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#0A3D6B',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'BA WAS ALSE',
                    data: @json($grafikPengawasanBulanan->pluck('alse')),
                    borderColor: '#F57C00',
                    backgroundColor: 'rgba(245, 124, 0, 0.08)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#F57C00',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { size: 12, family: 'Inter' } } },
                tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', cornerRadius: 8, padding: 10, titleFont: { family: 'Inter' }, bodyFont: { family: 'Inter' } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11, family: 'Inter' } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 11, family: 'Inter' } }, grid: { display: false } }
            }
        }
    });

    // Chart: Jenis Usaha
    new Chart(document.getElementById('chartJenisUsaha'), {
        type: 'doughnut',
        data: {
            labels: @json($grafikJenisUsaha->pluck('nama')),
            datasets: [{
                data: @json($grafikJenisUsaha->pluck('pelaku_usahas_count')),
                backgroundColor: ['#0A3D6B','#1976D2','#2E7D32','#F57C00','#C62828','#6A1B9A','#00838F','#4E342E'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11, family: 'Inter' } } }
            }
        }
    });

    // Chart: Provinsi
    new Chart(document.getElementById('chartProvinsi'), {
        type: 'bar',
        data: {
            labels: @json($grafikProvinsi->pluck('nama')),
            datasets: [{
                label: 'Jumlah Pelaku Usaha',
                data: @json($grafikProvinsi->pluck('pelaku_usahas_count')),
                backgroundColor: '#1976D2',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11, family: 'Inter' } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                y: { ticks: { font: { size: 11, family: 'Inter' } }, grid: { display: false } }
            }
        }
    });

    // Map
    const dashboardMap = L.map('dashboardMap').setView([-1.4748, 124.8421], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(dashboardMap);
    const markers = L.markerClusterGroup();
    @foreach($pelakuUsahaMap as $p)
        markers.addLayer(L.marker([{{ $p->latitude }}, {{ $p->longitude }}]).bindPopup(`<strong>{{ addslashes($p->nama_perusahaan) }}</strong>`));
    @endforeach
    dashboardMap.addLayer(markers);

    // Fix map rendering issue in tabs/containers
    setTimeout(function(){ dashboardMap.invalidateSize(); }, 300);
</script>
@endpush
