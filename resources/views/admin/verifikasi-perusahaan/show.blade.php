@extends('layouts.app')
@section('title', 'Detail Perusahaan')
@section('page-title', 'Detail Verifikasi Perusahaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.verifikasi-perusahaan.index') }}">Verifikasi Perusahaan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row fade-in">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-header border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h4 class="card-title fw-bold text-primary"><i class="fas fa-building me-2"></i> Profil Perusahaan</h4>
                @if($company->isPending())
                    <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                @elseif($company->isActive())
                    <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                @else
                    <span class="badge bg-danger px-3 py-2"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" class="rounded border p-1 me-4 shadow-sm" style="width: 100px; height: 100px; object-fit: contain;">
                    @else
                        <div class="rounded bg-light text-primary d-flex align-items-center justify-content-center fw-bold border me-4 shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($company->nama_perusahaan, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">{{ $company->nama_perusahaan }}</h3>
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ $company->alamat }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary-soft text-primary border border-primary"><i class="fas fa-hashtag me-1"></i> No. Pengajuan: {{ $company->nomor_pengajuan ?: '-' }}</span>
                            <span class="badge bg-light text-dark border"><i class="fas fa-id-card me-1 text-muted"></i> NIB: {{ $company->nib ?: '-' }}</span>
                            <span class="badge bg-light text-dark border"><i class="fas fa-file-invoice-dollar me-1 text-muted"></i> NPWP: {{ $company->npwp ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Email Perusahaan</label>
                        <div class="fw-semibold"><i class="fas fa-envelope text-primary me-2"></i>{{ $company->email_perusahaan ?: '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Nomor Telepon</label>
                        <div class="fw-semibold"><i class="fas fa-phone text-success me-2"></i>{{ $company->nomor_telepon ?: '-' }}</div>
                    </div>
                </div>

                <div class="bg-light p-3 rounded mb-4" style="border: 1px dashed #ced4da;">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="fas fa-user-tie me-2 text-primary"></i> Data Penanggung Jawab</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small mb-0">Nama Lengkap</label>
                            <div class="fw-bold text-dark">{{ $company->nama_penanggung_jawab }}</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small mb-0">Jabatan</label>
                            <div class="fw-bold text-dark">{{ $company->jabatan_penanggung_jawab ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-4">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="fas fa-file-alt me-2 text-primary"></i> Detail Dokumen Pengajuan</h6>
                    
                    <div class="mb-3">
                        <label class="text-muted small fw-bold mb-1">Daftar Dokumen yang Disertakan:</label>
                        <div class="p-2 bg-light rounded" style="border: 1px solid #e9ecef; white-space: pre-wrap;">{{ $company->dokumen_diunggah ?: 'Tidak dirincikan' }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold mb-1">Keterangan Tambahan:</label>
                        <div class="p-2 bg-light rounded" style="border: 1px solid #e9ecef; white-space: pre-wrap;">{{ $company->keterangan_tambahan ?: 'Tidak ada keterangan tambahan.' }}</div>
                    </div>

                    <div>
                        <label class="text-muted small fw-bold mb-2">File Dokumen PDF:</label>
                        <div>
                            @if($company->file_dokumen)
                                <a href="{{ asset('storage/'.$company->file_dokumen) }}" target="_blank" class="btn btn-outline-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Buka / Unduh Dokumen PDF
                                </a>
                            @else
                                <span class="badge bg-secondary">File tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        {{-- Card Akun Google --}}
        <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h6 class="card-title fw-bold text-dark m-0"><i class="fab fa-google text-danger me-2"></i> Akun Pendaftar (Google)</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $company->user->foto_profil ?: 'https://ui-avatars.com/api/?name='.urlencode($company->user->name).'&background=random' }}" class="rounded-circle me-3" width="48" height="48">
                    <div>
                        <div class="fw-bold text-dark">{{ $company->user->name }}</div>
                        <div class="text-muted small">{{ $company->user->email }}</div>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="far fa-calendar-alt me-1"></i> Terdaftar pada: {{ $company->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>

        {{-- Log Verifikasi & Catatan Admin --}}
        @if(!$company->isPending())
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h6 class="card-title fw-bold text-dark m-0"><i class="fas fa-history text-secondary me-2"></i> Riwayat & Catatan Admin</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="text-muted small mb-0">Diproses Oleh:</label>
                        <div class="fw-semibold">{{ $company->verifiedBy?->name ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-0">Tanggal Proses:</label>
                        <div class="fw-semibold">{{ $company->verified_at ? $company->verified_at->format('d M Y H:i') : '-' }}</div>
                    </div>

                    @if($company->isRejected())
                        <div class="alert alert-danger border-0 p-3 mb-0" style="border-radius: 8px;">
                            <label class="text-danger small fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Alasan Penolakan:</label>
                            <div class="small">{{ $company->rejection_reason }}</div>
                        </div>
                    @elseif($company->catatan_admin)
                        <div class="alert alert-info border-0 p-3 mb-0" style="border-radius: 8px;">
                            <label class="text-info small fw-bold mb-1"><i class="fas fa-comment-dots me-1"></i> Catatan Admin:</label>
                            <div class="small">{{ $company->catatan_admin }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Aksi Verifikasi (Muncul Jika PENDING atau REVISION) --}}
        @if($company->isPending() || $company->isRevision())
            <div class="card shadow-sm border-warning" style="border-radius: 12px;">
                <div class="card-header bg-warning-soft border-bottom-0 pt-3">
                    <h6 class="card-title fw-bold text-dark m-0"><i class="fas fa-gavel me-2"></i> Tindakan Verifikasi</h6>
                </div>
                <div class="card-body bg-light" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <p class="small text-muted mb-4">Silakan periksa kelengkapan dokumen pengajuan ini.</p>
                    
                    <button type="button" class="btn btn-success w-100 fw-bold mb-2" data-bs-toggle="modal" data-bs-target="#modalApprove">
                        <i class="fas fa-check me-2"></i> Setujui Pengajuan
                    </button>
                    
                    <button type="button" class="btn btn-warning w-100 fw-bold mb-2 text-dark" data-bs-toggle="modal" data-bs-target="#modalRevision">
                        <i class="fas fa-pencil-alt me-2"></i> Perlu Revisi
                    </button>

                    <button type="button" class="btn btn-outline-danger w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modalReject">
                        <i class="fas fa-times me-2"></i> Tolak Pengajuan
                    </button>
                </div>
            </div>

        @endif

    </div>
</div> <!-- End of fade-in row -->

@push('modals')
            {{-- Modal Approve --}}
            <div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px;">
                        <form action="{{ route('admin.verifikasi-perusahaan.approve', $company->id) }}" method="POST" id="formApprove">
                            @csrf
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-success"><i class="fas fa-check-circle me-2"></i> Setujui Pengajuan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small mb-3">Tindakan ini akan menyetujui dokumen yang dikirimkan. Jika ada pesan untuk pengguna, isikan di bawah.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Catatan Opsional</label>
                                    <textarea name="catatan_admin" rows="3" class="form-control" placeholder="Cth: Dokumen sudah sesuai..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4" id="btnApprove"><i class="fas fa-check me-2"></i> Setujui Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Revision --}}
            <div class="modal fade" id="modalRevision" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px;">
                        <form action="{{ route('admin.verifikasi-perusahaan.revision', $company->id) }}" method="POST" id="formRevision">
                            @csrf
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-warning"><i class="fas fa-pencil-alt me-2"></i> Minta Revisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small mb-3">Pengajuan akan dikembalikan dengan status 'Perlu Revisi'. Tuliskan bagian mana yang perlu diperbaiki.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Catatan Revisi <span class="text-danger">*</span></label>
                                    <textarea name="catatan_admin" id="catatan_revisi" rows="4" class="form-control" required minlength="5" placeholder="Cth: Mohon lampirkan scan KTP penanggung jawab yang lebih jelas..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning rounded-pill px-4" id="btnRevision"><i class="fas fa-paper-plane me-2"></i> Kirim Revisi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Reject --}}
            <div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px;">
                        <form action="{{ route('admin.verifikasi-perusahaan.reject', $company->id) }}" method="POST" id="formReject">
                            @csrf
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-times-circle me-2"></i> Tolak Pengajuan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small mb-3">Tuliskan alasan penolakan secara jelas. Alasan ini akan dibaca oleh perusahaan di dashboard mereka.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control" required minlength="10" placeholder="Cth: Dokumen tidak relevan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4" id="btnReject"><i class="fas fa-paper-plane me-2"></i> Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formApprove = document.getElementById('formApprove');
        if(formApprove) {
            formApprove.addEventListener('submit', function() {
                const btn = document.getElementById('btnApprove');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                btn.disabled = true;
            });
        }

        const formRevision = document.getElementById('formRevision');
        if(formRevision) {
            formRevision.addEventListener('submit', function(e) {
                const text = document.getElementById('catatan_revisi').value;
                if(text.length < 5) {
                    e.preventDefault();
                    alert('Mohon isi Catatan Revisi minimal 5 karakter!');
                    return false;
                }
                const btn = document.getElementById('btnRevision');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                btn.disabled = true;
            });
        }

        const formReject = document.getElementById('formReject');
        if(formReject) {
            formReject.addEventListener('submit', function(e) {
                const text = document.getElementById('rejection_reason').value;
                if(text.length < 10) {
                    e.preventDefault();
                    alert('Mohon isi Alasan Penolakan minimal 10 karakter!');
                    return false;
                }
                const btn = document.getElementById('btnReject');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                btn.disabled = true;
            });
        }
    });
</script>
@endpush
@endsection
