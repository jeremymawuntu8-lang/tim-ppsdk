@extends('layouts.app')

@section('title', 'Jadwal Pengawasan')

@push('styles')
<style>
    .stat-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .stat-card .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .stat-card-hari-ini .icon-box { background-color: #e67e22; color: white; }
    .stat-card-akan-datang .icon-box { background-color: #3b82f6; color: white; }
    .stat-card-sudah-lewat .icon-box { background-color: #6b7280; color: white; }
    
    .stat-number { font-size: 2.5rem; font-weight: 700; line-height: 1; color: #1e3a8a; }
    .stat-label { font-size: 0.85rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-top: 5px; }
    
    .filter-tabs {
        background: white;
        border-radius: 10px;
        padding: 0.5rem;
        display: flex;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
    }
    .filter-btn {
        border: none;
        background: transparent;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        color: #4b5563;
        transition: all 0.2s;
    }
    .filter-btn:hover { background: #f3f4f6; }
    .filter-btn.active {
        background: #3b82f6;
        color: white;
    }

    .jadwal-card {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
        transition: transform 0.2s;
        display: flex;
        flex-direction: row;
        overflow: hidden;
    }
    .jadwal-card:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.08); }
    
    .jadwal-date-section {
        padding: 1.5rem;
        text-align: center;
        border-right: 1px dashed #e5e7eb;
        min-width: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .jadwal-date-number { font-size: 2.5rem; font-weight: 800; color: #1e3a8a; line-height: 1; }
    .jadwal-date-month { font-size: 1rem; font-weight: 700; color: #4b5563; text-transform: uppercase; margin-top: 5px; margin-bottom: 10px; }
    .jadwal-time-badge { background: #1e3a8a; color: white; border-radius: 20px; padding: 4px 12px; font-size: 0.85rem; font-weight: 600; }
    
    .jadwal-details {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .jadwal-info-main { flex: 1; }
    .company-name { font-size: 1.25rem; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px; }
    .person-name { color: #6b7280; font-weight: 500; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; margin-bottom: 0.25rem; }
    .ba-type { color: #6b7280; font-size: 0.9rem; margin-bottom: 0.75rem; }
    
    .jadwal-actions { display: flex; flex-direction: column; gap: 0.5rem; min-width: 120px; }
    .btn-action-primary { background: #1e3a8a; color: white; border: none; font-weight: 600; padding: 8px 16px; border-radius: 20px;}
    .btn-action-primary:hover { background: #172a6b; color: white; }
    .btn-action-success { background: #10b981; color: white; border: none; font-weight: 600; padding: 8px 16px; border-radius: 20px;}
    .btn-action-success:hover { background: #059669; color: white; }

    /* Media query for mobile */
    @media (max-width: 768px) {
        .jadwal-card { flex-direction: column; }
        .jadwal-date-section { border-right: none; border-bottom: 1px dashed #e5e7eb; min-width: auto; padding: 1rem; }
        .jadwal-details { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .jadwal-actions { width: 100%; }
        .jadwal-actions .btn { width: 100%; }
        .filter-tabs { flex-wrap: wrap; }
        .filter-btn { flex: 1; text-align: center; }
    }
</style>
@endpush

@section('content')
@php
    $today = date('Y-m-d');
    $countHariIni = 0;
    $countAkanDatang = 0;
    $countSudahLewat = 0;
    
    foreach($jadwals as $j) {
        if(!$j->tanggal_pengawasan) continue;
        if($j->tanggal_pengawasan == $today) $countHariIni++;
        elseif($j->tanggal_pengawasan > $today) $countAkanDatang++;
        else $countSudahLewat++;
    }
@endphp

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card stat-card stat-card-hari-ini h-100">
            <div class="card-body">
                <div class="icon-box"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number">{{ $countHariIni }}</div>
                <div class="stat-label">Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card stat-card stat-card-akan-datang h-100" style="background-color: #eff6ff;">
            <div class="card-body">
                <div class="icon-box"><i class="fas fa-clock"></i></div>
                <div class="stat-number">{{ $countAkanDatang }}</div>
                <div class="stat-label">Akan Datang</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-card-sudah-lewat h-100">
            <div class="card-body">
                <div class="icon-box"><i class="fas fa-history"></i></div>
                <div class="stat-number">{{ $countSudahLewat }}</div>
                <div class="stat-label">Sudah Lewat</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-tabs">
    <button class="filter-btn" data-filter="hari_ini"><i class="fas fa-cog me-2"></i>Hari Ini</button>
    <button class="filter-btn active" data-filter="akan_datang"><i class="fas fa-arrow-right me-2"></i>Akan Datang</button>
    <button class="filter-btn" data-filter="sudah_lewat"><i class="fas fa-hourglass-end me-2"></i>Sudah Lewat</button>
    <button class="filter-btn" data-filter="semua"><i class="fas fa-list me-2"></i>Semua</button>
</div>

<!-- List Jadwal -->
<div class="jadwal-list" id="jadwalContainer">
    @forelse($jadwals as $j)
        @php
            if(!$j->tanggal_pengawasan) continue;
            
            $cat = 'sudah_lewat';
            if($j->tanggal_pengawasan == $today) $cat = 'hari_ini';
            elseif($j->tanggal_pengawasan > $today) $cat = 'akan_datang';
            
            $timestamp = strtotime($j->tanggal_pengawasan);
            $day = date('d', $timestamp);
            
            // Format month manually to ensure Indonesian or standard format
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            $monthIndex = (int)date('m', $timestamp) - 1;
            $month = $months[$monthIndex] . ' ' . date('Y', $timestamp);
            
            $badgeColor = match($j->status) {
                'selesai', 'tindak_lanjut' => 'success',
                'proses', 'sedang_berjalan' => 'warning',
                'draft', 'belum_dilaksanakan' => 'secondary',
                'dibatalkan' => 'danger',
                default => 'secondary'
            };
        @endphp
        <div class="jadwal-card {{ $cat != 'akan_datang' ? 'd-none' : '' }}" data-category="{{ $cat }}">
            <div class="jadwal-date-section">
                <div class="jadwal-date-number">{{ $day }}</div>
                <div class="jadwal-date-month">{{ $month }}</div>
                @if($j->jam_wita)
                    <div class="jadwal-time-badge"><i class="fas fa-clock me-1"></i>{{ $j->jam_wita }}</div>
                @endif
            </div>
            <div class="jadwal-details">
                <div class="jadwal-info-main">
                    <div class="company-name"><i class="fas fa-building text-primary"></i> {{ $j->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}</div>
                    <div class="person-name"><i class="fas fa-user-tie"></i> {{ $j->tim_pengawas ?? 'Tim Pengawas belum ditentukan' }}</div>
                    <div class="ba-type">{{ $j->jenis_pengawasan }}</div>
                    <div>
                        <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-2"><i class="fas fa-info-circle me-1"></i> {{ ucwords(str_replace('_', ' ', $j->status)) }}</span>
                    </div>
                </div>
                <div class="jadwal-actions">
                    <a href="{{ $j->url }}" class="btn btn-action-primary btn-sm"><i class="fas fa-file-alt me-1"></i> Lihat Detail</a>
                    @if($j->nomor_hp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $j->nomor_hp) }}" target="_blank" class="btn btn-action-success btn-sm"><i class="fab fa-whatsapp me-1"></i> Hubungi</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center p-5 bg-white rounded shadow-sm" id="emptyState">
            <h5 class="text-muted"><i class="fas fa-folder-open me-2"></i>Tidak ada jadwal pengawasan.</h5>
        </div>
    @endforelse
    <div class="text-center p-5 bg-white rounded shadow-sm d-none" id="emptyFilterState">
        <h5 class="text-muted"><i class="fas fa-filter me-2"></i>Tidak ada jadwal untuk kategori ini.</h5>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initial check for empty filter state
        checkEmptyState();
        
        $('.filter-btn').on('click', function() {
            // Update active state
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            let filter = $(this).data('filter');
            
            // Show/hide cards based on filter
            $('.jadwal-card').each(function() {
                if(filter === 'semua' || $(this).data('category') === filter) {
                    $(this).removeClass('d-none');
                } else {
                    $(this).addClass('d-none');
                }
            });
            
            checkEmptyState();
        });
        
        function checkEmptyState() {
            let visibleCount = $('.jadwal-card:not(.d-none)').length;
            if(visibleCount === 0 && $('.jadwal-card').length > 0) {
                $('#emptyFilterState').removeClass('d-none');
            } else {
                $('#emptyFilterState').addClass('d-none');
            }
        }
    });
</script>
@endpush
