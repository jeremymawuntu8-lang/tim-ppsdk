@extends('layouts.company')
@section('title', 'Dashboard Perusahaan')
@section('page-title', 'Dashboard')

@section('content')
<div class="fade-in">

    {{-- STATUS: PENDING --}}
    @if($company->isPending())
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card text-center border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body py-5 px-4">
                        <div class="mb-4" style="font-size: 4rem;">⏳</div>
                        <h4 class="fw-bold text-warning mb-2">Menunggu Verifikasi Admin</h4>
                        <p class="text-muted mb-4">
                            Profil perusahaan <strong>{{ $company->nama_perusahaan }}</strong> sedang dalam proses review oleh tim admin kami.
                            Anda akan dapat menggunakan fitur upload dokumen setelah akun diverifikasi.
                        </p>
                        <div class="alert alert-warning bg-warning-soft border-0" style="border-radius: 12px;">
                            <i class="fas fa-info-circle me-2"></i>
                            Proses verifikasi biasanya membutuhkan waktu <strong>1×24 jam</strong> di hari kerja.
                        </div>

                        <div class="card bg-light border-0 mt-4 text-start" style="border-radius: 12px;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-building me-2 text-primary"></i>Ringkasan Profil Yang Dikirim</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><th width="40%">Nama Perusahaan</th><td>{{ $company->nama_perusahaan }}</td></tr>
                                    <tr><th>NIB</th><td>{{ $company->nib ?: '-' }}</td></tr>
                                    <tr><th>NPWP</th><td>{{ $company->npwp ?: '-' }}</td></tr>
                                    <tr><th>Penanggung Jawab</th><td>{{ $company->nama_penanggung_jawab }}</td></tr>
                                    <tr><th>Terdaftar Pada</th><td>{{ $company->created_at->format('d M Y H:i') }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- STATUS: REJECTED --}}
    @elseif($company->isRejected())
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card text-center border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body py-5 px-4">
                        <div class="mb-4" style="font-size: 4rem;">❌</div>
                        <h4 class="fw-bold text-danger mb-2">Pendaftaran Ditolak</h4>
                        <p class="text-muted mb-4">
                            Mohon maaf, pendaftaran perusahaan <strong>{{ $company->nama_perusahaan }}</strong> tidak dapat disetujui saat ini.
                        </p>

                        @if($company->rejection_reason)
                            <div class="alert alert-danger border-0 text-start" style="border-radius: 12px;">
                                <h6 class="fw-bold mb-2"><i class="fas fa-times-circle me-2"></i>Alasan Penolakan:</h6>
                                <p class="mb-0">{{ $company->rejection_reason }}</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <p class="text-muted small">Jika ada pertanyaan, silakan hubungi administrator sistem.</p>
                            <a href="mailto:admin@ppsdk.go.id" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i> Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- STATUS: REVISION --}}
    @elseif($company->isRevision())
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card text-center border-warning shadow-sm" style="border-radius: 20px;">
                    <div class="card-body py-5 px-4 bg-warning-soft" style="border-radius: 20px;">
                        <div class="mb-4" style="font-size: 4rem;">📝</div>
                        <h4 class="fw-bold text-dark mb-2">Pengajuan Perlu Direvisi</h4>
                        <p class="text-muted mb-4">
                            Tim admin kami telah memeriksa dokumen yang Anda kirimkan dan menemukan beberapa hal yang perlu diperbaiki atau dilengkapi.
                        </p>

                        @if($company->catatan_admin)
                            <div class="alert bg-white border border-warning text-start shadow-sm mb-4" style="border-radius: 12px;">
                                <h6 class="fw-bold text-warning-dark mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Catatan Revisi dari Admin:</h6>
                                <p class="mb-0 text-dark">{{ $company->catatan_admin }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-muted small mb-3">Silakan perbaiki data dan dokumen Anda sesuai dengan catatan di atas.</p>
                            <a href="{{ route('company.profil.edit') }}" class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm text-dark">
                                <i class="fas fa-edit me-2"></i> Perbaiki Data Pengajuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- STATUS: ACTIVE --}}
    @elseif($company->isActive())
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 mb-4 shadow-sm text-center" style="background: linear-gradient(135deg, #0A3D6B 0%, #1565C0 100%); border-radius: 20px;">
                    <div class="card-body py-5 px-4 text-white">
                        <div class="mb-4">
                            <div class="d-inline-flex bg-white rounded-circle align-items-center justify-content-center shadow" style="width: 100px; height: 100px;">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-2 text-white">Pengajuan Disetujui!</h3>
                        <p class="mb-4 opacity-75">
                            Selamat! Dokumen perizinan perusahaan <strong>{{ $company->nama_perusahaan }}</strong> telah diverifikasi dan disetujui oleh admin PSDKP.
                        </p>
                        
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-6">
                                <div class="bg-white bg-opacity-10 rounded p-3 text-start">
                                    <div class="d-flex justify-content-between border-bottom border-light pb-2 mb-2">
                                        <span class="opacity-75 small">No. Pengajuan</span>
                                        <strong class="text-white">{{ $company->nomor_pengajuan ?: '-' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom border-light pb-2 mb-2">
                                        <span class="opacity-75 small">Tanggal Disetujui</span>
                                        <strong class="text-white">{{ $company->verified_at ? $company->verified_at->format('d M Y') : '-' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="opacity-75 small">Penanggung Jawab</span>
                                        <strong class="text-white">{{ $company->nama_penanggung_jawab }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($company->catatan_admin)
                            <div class="mt-4 bg-white bg-opacity-10 rounded p-3 text-start">
                                <strong class="text-white d-block mb-1"><i class="fas fa-comment-dots me-2"></i>Pesan dari Admin:</strong>
                                <span class="opacity-75 small">{{ $company->catatan_admin }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
