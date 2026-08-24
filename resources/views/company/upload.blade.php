@extends('layouts.company')
@section('title', 'Upload Dokumen Lanjutan')
@section('page-title', 'Upload Dokumen Lanjutan')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body text-center p-5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-4 opacity-50" style="max-height: 100px;">
                <h4 class="fw-bold text-dark mb-3">Fitur Sedang Dalam Pengembangan</h4>
                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                    Selamat! Perusahaan Anda telah berstatus aktif. Fitur upload dokumen lanjutan (seperti laporan tahunan atau dokumen tambahan) saat ini sedang dalam tahap pengembangan dan akan segera hadir.
                </p>
                <a href="{{ route('company.dashboard') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
