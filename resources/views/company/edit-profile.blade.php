@extends('layouts.company')
@section('title', 'Perbaiki Dokumen Perizinan')
@section('page-title', 'Perbaiki Dokumen')

@push('styles')
    <style>
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #cbd5e1;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #0A3D6B;
            box-shadow: 0 0 0 4px rgba(10, 61, 107, 0.1);
        }
        .upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .upload-dropzone:hover, .upload-dropzone.dragover {
            border-color: #0A3D6B;
            background-color: #eef2f6;
        }
        .upload-dropzone i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }
        .upload-dropzone:hover i, .upload-dropzone.dragover i {
            color: #0A3D6B;
        }
        .btn-primary-custom {
            background: #0A3D6B;
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background: #1565C0;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(10, 61, 107, 0.2);
        }
        .required-star { color: #dc3545; }
    </style>
@endpush

@section('content')
<div class="fade-in">
    @if($company->isRevision() && $company->catatan_admin)
        <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 12px;">
            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Catatan Revisi dari Admin:</h6>
            <p class="mb-0">{{ $company->catatan_admin }}</p>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold" style="color: #0A3D6B;"><i class="fas fa-edit me-2"></i> Formulir Dokumen Perizinan</h5>
            <p class="text-muted small">Silakan perbaiki atau lengkapi data Anda.</p>
        </div>

        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger bg-danger-soft border-0 mb-4" style="border-radius: 10px;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-circle fs-5 me-2 text-danger"></i>
                        <strong class="text-danger">Terdapat kesalahan pengisian:</strong>
                    </div>
                    <ul class="mb-0 text-danger small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('company.profil.update') }}" enctype="multipart/form-data" id="uploadForm">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">
                    {{-- 1. Email --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Email <span class="required-star">*</span></label>
                        <input type="email" class="form-control bg-light text-muted" value="{{ auth()->user()->email }}" readonly>
                        <div class="form-text small"><i class="fas fa-info-circle me-1"></i> Email terisi otomatis dari akun Google Anda.</div>
                    </div>

                    {{-- 2. Nama Perusahaan --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Nama Perusahaan <span class="required-star">*</span></label>
                        <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                               value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" required placeholder="Masukkan nama perusahaan">
                        @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 3. Tanggal --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal <span class="required-star">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $company->tanggal ? $company->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 4. Nama Penanggung Jawab --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Penanggung Jawab <span class="required-star">*</span></label>
                        <input type="text" name="nama_penanggung_jawab" class="form-control @error('nama_penanggung_jawab') is-invalid @enderror"
                               value="{{ old('nama_penanggung_jawab', $company->nama_penanggung_jawab) }}" required placeholder="Nama lengkap">
                        @error('nama_penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 5. Jabatan Penanggung Jawab --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan <span class="required-star">*</span></label>
                        <input type="text" name="jabatan_penanggung_jawab" class="form-control @error('jabatan_penanggung_jawab') is-invalid @enderror"
                               value="{{ old('jabatan_penanggung_jawab', $company->jabatan_penanggung_jawab) }}" required placeholder="Jabatan">
                        @error('jabatan_penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 6. Nomor Telepon --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Nomor yang Bisa Dihubungi <span class="required-star">*</span></label>
                        <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror"
                               value="{{ old('nomor_telepon', $company->nomor_telepon) }}" required placeholder="Nomor telepon">
                        @error('nomor_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 6. Dokumen Diunggah --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Dokumen/Perizinan yang Diunggah <span class="required-star">*</span></label>
                        <textarea name="dokumen_diunggah" class="form-control @error('dokumen_diunggah') is-invalid @enderror"
                                  rows="10" required placeholder="Tuliskan seluruh dokumen yang Anda lampirkan dalam file PDF.">{{ old('dokumen_diunggah', $company->dokumen_diunggah) }}</textarea>
                        @error('dokumen_diunggah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- 7. Keterangan Tambahan --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Keterangan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="keterangan_tambahan" class="form-control @error('keterangan_tambahan') is-invalid @enderror"
                                  rows="3" placeholder="Tuliskan informasi tambahan apabila diperlukan.">{{ old('keterangan_tambahan', $company->keterangan_tambahan) }}</textarea>
                        @error('keterangan_tambahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr class="my-5 border-light">
                <h5 class="fw-bold mb-4" style="color: #0A3D6B;"><i class="fas fa-cloud-upload-alt me-2"></i> Update Dokumen PDF</h5>
                
                @if($company->file_dokumen)
                <div class="alert alert-success border-0 bg-success-soft d-flex align-items-center mb-4" style="border-radius: 10px;">
                    <i class="fas fa-file-pdf fs-3 text-success me-3"></i>
                    <div>
                        <strong class="d-block text-success">File saat ini sudah terunggah</strong>
                        <span class="small text-success opacity-75">Anda tidak perlu mengupload ulang file jika tidak ada perubahan dokumen.</span>
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <div class="upload-dropzone" id="dropzone">
                        <i class="fas fa-file-pdf"></i>
                        <h5 class="fw-bold text-dark mb-2">Tarik & Letakkan File PDF Baru di Sini (Opsional)</h5>
                        <p class="text-muted small mb-3">klik untuk memilih file dari perangkat Anda (Maks. 1 GB)</p>
                        
                        <input type="file" name="file_dokumen" id="file_dokumen" class="d-none" accept=".pdf">
                        <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="document.getElementById('file_dokumen').click()">
                            Pilih File PDF Baru
                        </button>
                        
                        <div id="fileNameDisplay" class="mt-3 fw-semibold text-primary d-none">
                            <i class="fas fa-check-circle me-1 text-success"></i> <span id="fileNameText"></span>
                        </div>
                    </div>
                    @error('file_dokumen')
                        <div class="text-danger small mt-2 fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary-custom text-white shadow" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Simpan & Kirim Ulang Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const dropzone = $('#dropzone');
        const fileInput = $('#file_dokumen');
        const fileNameDisplay = $('#fileNameDisplay');
        const fileNameText = $('#fileNameText');
        const form = $('#uploadForm');
        const submitBtn = $('#submitBtn');

        // Drag & Drop events
        dropzone.on('dragover', function(e) {
            e.preventDefault();
            dropzone.addClass('dragover');
        });

        dropzone.on('dragleave', function(e) {
            e.preventDefault();
            dropzone.removeClass('dragover');
        });

        dropzone.on('drop', function(e) {
            e.preventDefault();
            dropzone.removeClass('dragover');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                fileInput[0].files = files;
                updateFileName(files[0]);
            }
        });

        // File input change event
        fileInput.on('change', function() {
            if (this.files.length > 0) {
                updateFileName(this.files[0]);
            }
        });

        function updateFileName(file) {
            if (file.type !== 'application/pdf') {
                alert('Hanya file PDF yang diperbolehkan!');
                fileInput.val('');
                fileNameDisplay.addClass('d-none');
                return;
            }
            
            if (file.size > 1073741824) {
                alert('Ukuran file terlalu besar. Maksimal 1 GB.');
                fileInput.val('');
                fileNameDisplay.addClass('d-none');
                return;
            }

            fileNameText.text(file.name + ' (' + formatBytes(file.size) + ')');
            fileNameDisplay.removeClass('d-none');
            dropzone.css('border-color', '#0A3D6B');
        }

        function formatBytes(bytes, decimals = 2) {
            if (!+bytes) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
        }

        // Loading state on submit
        form.on('submit', function() {
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan Perubahan...');
            submitBtn.prop('disabled', true);
        });
    });
</script>
@endpush
