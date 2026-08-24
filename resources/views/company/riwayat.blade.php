@extends('layouts.company')
@section('title', 'Riwayat Pengajuan')
@section('page-title', 'Riwayat Pengajuan')

@push('styles')
<style>
    .timeline { position: relative; padding-left: 2.75rem; }
    .timeline-item { position: relative; padding-bottom: 2rem; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -2.05rem;
        top: 2.2rem;
        bottom: -0.5rem;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-marker {
        position: absolute;
        left: -2.75rem;
        top: 0;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.9rem;
        box-shadow: 0 0 0 4px #fff;
    }
</style>
@endpush

@section('content')
<div class="fade-in">

    {{-- Ringkasan Pengajuan --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color: #0A3D6B;">{{ $company->nama_perusahaan }}</h5>
                    <div class="text-muted small">No. Pengajuan: <strong>{{ $company->nomor_pengajuan ?: '-' }}</strong></div>
                </div>
                @php
                    $statusMap = [
                        'pending'  => ['label' => 'Menunggu Verifikasi', 'color' => 'warning', 'icon' => 'clock'],
                        'revision' => ['label' => 'Perlu Direvisi',      'color' => 'warning', 'icon' => 'pencil-alt'],
                        'active'   => ['label' => 'Disetujui',           'color' => 'success', 'icon' => 'check-circle'],
                        'rejected' => ['label' => 'Ditolak',             'color' => 'danger',  'icon' => 'times-circle'],
                    ];
                    $st = $statusMap[$company->status] ?? ['label' => ucfirst($company->status), 'color' => 'secondary', 'icon' => 'circle'];
                @endphp
                <span class="badge bg-{{ $st['color'] }}-soft text-{{ $st['color'] }} px-3 py-2" style="border-radius: 20px; font-size: 0.85rem;">
                    <i class="fas fa-{{ $st['icon'] }} me-1"></i> {{ $st['label'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- Linimasa --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <h6 class="fw-bold mb-0" style="color: #0A3D6B;"><i class="fas fa-history me-2"></i>Linimasa Pengajuan</h6>
        </div>
        <div class="card-body p-4">
            <div class="timeline">

                {{-- 1. Diajukan --}}
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"><i class="fas fa-paper-plane"></i></div>
                    <h6 class="fw-bold mb-1">Dokumen Diajukan</h6>
                    <p class="text-muted small mb-2">{{ $company->created_at->format('d M Y, H:i') }} WIB</p>
                    @if($company->file_dokumen)
                        <a href="{{ asset('storage/'.$company->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen yang Diunggah
                        </a>
                    @endif
                </div>

                {{-- 2. Status terkini --}}
                @if($company->isPending())
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"><i class="fas fa-clock"></i></div>
                        <h6 class="fw-bold mb-1">Menunggu Verifikasi Admin</h6>
                        <p class="text-muted small mb-0">Dokumen Anda sedang diperiksa oleh tim admin. Proses biasanya membutuhkan waktu 1×24 jam di hari kerja.</p>
                    </div>
                @elseif($company->isRevision())
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"><i class="fas fa-pencil-alt"></i></div>
                        <h6 class="fw-bold mb-1">Diminta Revisi</h6>
                        <p class="text-muted small mb-2">
                            {{ $company->verified_at?->format('d M Y, H:i') ?? '-' }} WIB
                            @if($company->verifiedBy) &middot; oleh {{ $company->verifiedBy->name }} @endif
                        </p>
                        @if($company->catatan_admin)
                            <div class="alert bg-warning-soft border-0 small mb-3" style="border-radius: 10px;">
                                <strong>Catatan Admin:</strong> {{ $company->catatan_admin }}
                            </div>
                        @endif
                        <a href="{{ route('company.profil.edit') }}" class="btn btn-sm btn-warning rounded-pill fw-semibold text-dark">
                            <i class="fas fa-edit me-1"></i> Perbaiki Sekarang
                        </a>
                    </div>
                @elseif($company->isActive())
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"><i class="fas fa-check"></i></div>
                        <h6 class="fw-bold mb-1">Disetujui</h6>
                        <p class="text-muted small mb-2">
                            {{ $company->verified_at?->format('d M Y, H:i') ?? '-' }} WIB
                            @if($company->verifiedBy) &middot; oleh {{ $company->verifiedBy->name }} @endif
                        </p>
                        @if($company->catatan_admin)
                            <div class="alert bg-success-soft border-0 small mb-0" style="border-radius: 10px;">
                                <strong>Pesan Admin:</strong> {{ $company->catatan_admin }}
                            </div>
                        @endif
                    </div>
                @elseif($company->isRejected())
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"><i class="fas fa-times"></i></div>
                        <h6 class="fw-bold mb-1">Pendaftaran Ditolak</h6>
                        <p class="text-muted small mb-2">
                            {{ $company->verified_at?->format('d M Y, H:i') ?? '-' }} WIB
                            @if($company->verifiedBy) &middot; oleh {{ $company->verifiedBy->name }} @endif
                        </p>
                        @if($company->rejection_reason)
                            <div class="alert bg-danger-soft border-0 small mb-0" style="border-radius: 10px;">
                                <strong>Alasan Penolakan:</strong> {{ $company->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection