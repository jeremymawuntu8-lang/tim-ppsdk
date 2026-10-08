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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Laporan Tahunan 2026" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                            <div class="form-text small text-muted">Format: PDF, Word, Excel, Gambar. Maks: 5MB.</div>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan Tambahan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
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
                                        <div class="fw-bold text-dark">{{ $doc->judul }}</div>
                                        <div class="small text-muted"><i class="fas fa-file-alt me-1"></i> {{ $doc->nama_file }}</div>
                                    </td>
                                    <td>{{ $doc->keterangan ?? '-' }}</td>
                                    <td>{{ $doc->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('company.document.download', $doc->id) }}" class="btn btn-sm btn-outline-primary" title="Unduh" target="_blank">
                                                <i class="fas fa-download"></i>
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
