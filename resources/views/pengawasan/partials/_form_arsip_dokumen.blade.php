@props(['ba' => null])

<div class="card card-outline card-secondary mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0 fw-bold text-dark">
            <i class="fas fa-archive me-2 text-primary"></i> Arsip Dokumen Lama / Pra-Rilis <span class="badge bg-secondary-subtle text-secondary border ms-2 small">Opsional</span>
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            Gunakan bagian ini jika Anda menginput data Berita Acara lama sebelum website ini dirilis, dan ingin melampirkan berkas dokumen yang sebelumnya sudah ada atau tautan folder Google Drive arsip.
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fab fa-google-drive text-success me-1"></i> Tautan / Link Google Drive Arsip
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                    <input type="text" name="link_gdrive_arsip" class="form-control" placeholder="https://drive.google.com/drive/folders/..." value="{{ old('link_gdrive_arsip') }}">
                </div>
                <div class="form-text text-muted">Tautan folder atau file di Google Drive yang mengarahkan ke arsip dokumen sebelumnya.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-file-upload text-primary me-1"></i> Unggah Berkas Dokumen Lama
                </label>
                <input type="file" name="files_arsip[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
                <div class="form-text text-muted">Bisa memilih beberapa berkas sekaligus (PDF, Word, Excel, Scan Gambar).</div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Catatan / Keterangan Dokumen Arsip <small class="text-muted fw-normal">(Opsional)</small></label>
                <input type="text" name="keterangan_arsip" class="form-control" placeholder="Contoh: Dokumen fisik scan hasil pengawasan lapangan pra-rilis" value="{{ old('keterangan_arsip') }}">
            </div>
        </div>

        @if(!empty($ba) && $ba->arsipDokumen->isNotEmpty())
            <div class="mt-4 pt-3 border-top">
                <div class="fw-semibold small text-muted mb-2">Arsip yang sudah tersimpan pada BA ini:</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($ba->arsipDokumen as $arsip)
                        <span class="badge bg-light text-dark border p-2 d-inline-flex align-items-center">
                            <i class="{{ $arsip->icon_class }} me-2"></i> {{ $arsip->judul }}
                            @if($arsip->isLink())
                                <a href="{{ $arsip->link_gdrive }}" target="_blank" class="ms-2 text-success" title="Buka"><i class="fas fa-external-link-alt"></i></a>
                            @else
                                <a href="{{ route('arsip-dokumen-ba.download', $arsip->id) }}" class="ms-2 text-primary" title="Unduh"><i class="fas fa-download"></i></a>
                            @endif
                        </span>
                    @endforeach
                </div>
                <div class="form-text text-muted mt-2">
                    <i class="fas fa-info-circle me-1"></i> Pengelolaan penuh (tambah berkas baru atau hapus berkas) dapat dilakukan langsung di halaman <strong>Detail BA</strong>.
                </div>
            </div>
        @endif
    </div>
</div>
