@extends('layouts.app')
@section('title', 'Monitoring')
@section('page-title', 'Monitoring Jadwal Pengawasan')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">Monitoring</li>@endsection

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4 fade-in">
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-blue">
            <i class="fas fa-list-check stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($total) }}</div>
                <div class="stat-label">Total Jadwal</div>
            </div>
            <div class="stat-footer">Semua pengawasan terdaftar</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-amber">
            <i class="fas fa-hourglass-start stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($belum) }}</div>
                <div class="stat-label">Belum Dilaksanakan</div>
            </div>
            <div class="stat-footer">Menunggu pelaksanaan</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-cyan">
            <i class="fas fa-spinner stat-icon" style="animation: spin 3s linear infinite;"></i>
            <div>
                <div class="stat-value">{{ number_format($berjalan) }}</div>
                <div class="stat-label">Sedang Berjalan</div>
            </div>
            <div class="stat-footer">Pengawasan aktif</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="stat-card stat-card-green">
            <i class="fas fa-circle-check stat-icon"></i>
            <div>
                <div class="stat-value">{{ number_format($selesai) }}</div>
                <div class="stat-label">Sudah Selesai</div>
            </div>
            <div class="stat-footer">Tugas telah dirampungkan</div>
        </div>
    </div>
</div>

<div class="row g-4 fade-in">
    {{-- Chart Section --}}
    <div class="col-lg-7">
        <div class="card card-primary card-outline h-100" style="animation-delay: 0.1s">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-chart-column me-2 text-primary"></i>Grafik Pengawasan Bulanan</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 350px;">
                    <canvas id="chartMonitoring"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Section --}}
    <div class="col-lg-5">
        <div class="card card-primary card-outline h-100" style="animation-delay: 0.15s">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-stream me-2 text-primary"></i>Timeline Jadwal Mendatang</h3>
                <a href="{{ route('jadwal.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
                @forelse($timeline as $t)
                    @php
                        $statusColors = [
                            'belum_dilaksanakan' => 'amber', 
                            'sedang_berjalan' => 'cyan', 
                            'selesai' => 'green', 
                            'dibatalkan' => 'danger'
                        ];
                        $sColor = $statusColors[$t->status] ?? 'secondary';
                    @endphp
                    <div class="activity-item">
                        <div class="activity-dot bg-{{ $sColor }}"></div>
                        <div class="activity-content w-100 pe-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <strong class="text-dark">{{ $t->pelakuUsaha->nama_perusahaan ?? '-' }}</strong>
                                <span class="badge bg-primary-soft text-primary">{{ strtoupper($t->jenis_pengawasan) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted text-sm"><i class="far fa-calendar-alt me-1"></i> {{ $t->tanggal_rencana->format('d M Y') }}</span>
                                <span class="badge bg-{{ $sColor }}-soft text-{{ $sColor }}">{{ ucwords(str_replace('_',' ',$t->status)) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-5">
                        <i class="fas fa-calendar-times empty-state-icon text-muted opacity-50"></i>
                        <div class="empty-state-title mt-2">Belum ada jadwal</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endpush

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartMonitoring').getContext('2d');

// Create Gradient
let gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(25, 118, 210, 0.8)');   
gradient.addColorStop(1, 'rgba(25, 118, 210, 0.2)');

new Chart(ctx, {
    type: 'bar',
    data: { 
        labels: @json($grafikBulanan->pluck('label')), 
        datasets: [{ 
            label: 'Jumlah Jadwal Pengawasan', 
            data: @json($grafikBulanan->pluck('total')), 
            backgroundColor: gradient,
            borderColor: '#1976D2',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
            barPercentage: 0.6
        }] 
    },
    options: { 
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', cornerRadius: 8, padding: 12, titleFont: { family: 'Inter' }, bodyFont: { family: 'Inter' } }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] } },
            x: { ticks: { font: { family: 'Inter' } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
