@extends('layouts.company')
@section('title', 'Upload Dokumen Lanjutan')
@section('page-title', 'Upload Dokumen Lanjutan')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-0">Upload Dokumen Baru</h5>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('company.upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bentuk Dokumen <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3 p-2 bg-light rounded-3 border">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipeFileCompany" value="file" checked onchange="toggleCompanyTipe('file')">
                                    <label class="form-check-label fw-semibold small text-dark" for="tipeFileCompany">
                                        <i class="fas fa-file-upload text-primary me-1"></i> Unggah File Dokumen
                                    </label>
                                </div>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipeLinkCompany" value="link" onchange="toggleCompanyTipe('link')">
                                    <label class="form-check-label fw-semibold small text-dark" for="tipeLinkCompany">
                                        <i class="fab fa-google-drive text-success me-1"></i> Tautan / Link Google Drive
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Laporan Tahunan 2026" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6" id="wrapperCompanyFile">
                            <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="inputCompanyFile" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                            <div class="form-text small text-muted">Format: PDF, Word, Excel, Gambar. Maks: 50MB.</div>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6" id="wrapperCompanyLink" style="display: none;">
                            <label class="form-label fw-semibold">URL Link Google Drive <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                                <input type="url" class="form-control @error('link_drive') is-invalid @enderror" name="link_drive" id="inputCompanyLink" placeholder="https://drive.google.com/file/d/...">
                            </div>
                            <div class="form-text small text-muted">Pastikan izin akses Google Drive disetel "Siapa saja yang memiliki link".</div>
                            @error('link_drive')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan Tambahan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnCompanySubmit">
                                <i class="fas fa-upload me-2"></i> Unggah Dokumen
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-0">Riwayat Dokumen Lanjutan</h5>
            </div>
            <div class="card-body p-4">
                @if($documents->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fs-1 mb-3 text-secondary opacity-50"></i>
                        <p class="mb-0">Belum ada dokumen lanjutan yang diunggah.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul Dokumen</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Unggah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                            <i class="{{ $doc->icon_class }} me-1"></i>
                                            {{ $doc->judul }}
                                            @if($doc->isLink())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem;">Drive</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            {{ $doc->isLink() ? 'Tautan Google Drive' : $doc->nama_file }}
                                        </div>
                                    </td>
                                    <td>{{ $doc->keterangan ?? '-' }}</td>
                                    <td>{{ $doc->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('company.document.download', $doc->id) }}" class="btn btn-sm {{ $doc->isLink() ? 'btn-outline-success' : 'btn-outline-primary' }}" title="{{ $doc->isLink() ? 'Buka Link Google Drive' : 'Unduh File' }}" target="_blank">
                                                <i class="{{ $doc->isLink() ? 'fas fa-external-link-alt' : 'fas fa-download' }}"></i>
                                            </a>
                                            <form action="{{ route('company.document.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCompanyTipe(type) {
    if (type === 'link') {
        $('#wrapperCompanyFile').hide();
        $('#inputCompanyFile').prop('required', false).val('');
        $('#wrapperCompanyLink').show();
        $('#inputCompanyLink').prop('required', true);
        $('#btnCompanySubmit').html('<i class="fab fa-google-drive me-2"></i> Simpan Link Drive');
    } else {
        $('#wrapperCompanyLink').hide();
        $('#inputCompanyLink').prop('required', false).val('');
        $('#wrapperCompanyFile').show();
        $('#inputCompanyFile').prop('required', true);
        $('#btnCompanySubmit').html('<i class="fas fa-upload me-2"></i> Unggah Dokumen');
    }
}
</script>
@endpush
