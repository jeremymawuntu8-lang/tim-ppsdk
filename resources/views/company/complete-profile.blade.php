@php
    // Standalone form upload dokumen
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Dokumen Perizinan | PSDKP Bitung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --psdkp-blue: #0A3D6B;
            --psdkp-blue-light: #1565C0;
            --psdkp-blue-soft: #eef2f6;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f4f6f9; 
            color: #334155;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #cbd5e1;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--psdkp-blue);
            box-shadow: 0 0 0 4px rgba(10, 61, 107, 0.1);
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: #ffffff;
        }
        .header-bg {
            background: linear-gradient(135deg, var(--psdkp-blue) 0%, var(--psdkp-blue-light) 100%);
            border-radius: 16px 16px 0 0;
            padding: 2.5rem 2rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .header-bg::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg width="40" height="40" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.05)"/></svg>');
            pointer-events: none;
        }
        /* Modern Drag & Drop Zone */
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
            border-color: var(--psdkp-blue);
            background-color: var(--psdkp-blue-soft);
        }
        .upload-dropzone i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }
        .upload-dropzone:hover i, .upload-dropzone.dragover i {
            color: var(--psdkp-blue);
        }
        .btn-primary-custom {
            background: var(--psdkp-blue);
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background: var(--psdkp-blue-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(10, 61, 107, 0.2);
        }
        .required-star { color: #dc3545; }
        
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
<div class="min-vh-100 d-flex flex-column py-5 px-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                
                <div class="text-center mb-4 fade-in">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PSDKP" style="height: 70px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));" class="mb-3">
                </div>

                <div class="card card-custom fade-in" style="animation-delay: 0.1s;">
                    <div class="header-bg text-center">
                        <h3 class="fw-bold mb-2">Upload Dokumen Perizinan</h3>
                        <p class="mb-0 text-white-50" style="font-size: 1.05rem; font-weight: 400; max-width: 600px; margin: 0 auto;">
                            Silakan lengkapi formulir berikut untuk mengirimkan dokumen perizinan kepada Pangkalan Pengawasan Sumber Daya Kelautan dan Perikanan Bitung.
                        </p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        
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

                        <form method="POST" action="{{ route('company.complete-profile.store') }}" enctype="multipart/form-data" id="uploadForm">
                            @csrf

                            <h5 class="fw-bold mb-4" style="color: var(--psdkp-blue);"><i class="fas fa-edit me-2"></i> Formulir Data</h5>
                            
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
                                           value="{{ old('nama_perusahaan') }}" required placeholder="Masukkan nama perusahaan">
                                    @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 3. Tanggal --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal <span class="required-star">*</span></label>
                                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                           value="{{ old('tanggal') ?? date('Y-m-d') }}" required>
                                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 4. Nama Penanggung Jawab --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Penanggung Jawab <span class="required-star">*</span></label>
                                    <input type="text" name="nama_penanggung_jawab" class="form-control @error('nama_penanggung_jawab') is-invalid @enderror"
                                           value="{{ old('nama_penanggung_jawab') }}" required placeholder="Nama lengkap">
                                    @error('nama_penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 5. Jabatan Penanggung Jawab --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jabatan <span class="required-star">*</span></label>
                                    <input type="text" name="jabatan_penanggung_jawab" class="form-control @error('jabatan_penanggung_jawab') is-invalid @enderror"
                                           value="{{ old('jabatan_penanggung_jawab') }}" required placeholder="Jabatan">
                                    @error('jabatan_penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 6. Nomor Telepon --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Nomor yang Bisa Dihubungi <span class="required-star">*</span></label>
                                    <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                           value="{{ old('nomor_telepon') }}" required placeholder="Nomor telepon">
                                    @error('nomor_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 6. Dokumen Diunggah --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Dokumen/Perizinan yang Diunggah <span class="required-star">*</span></label>
                                    <textarea name="dokumen_diunggah" class="form-control @error('dokumen_diunggah') is-invalid @enderror"
                                              rows="10" required placeholder="Tuliskan seluruh dokumen yang Anda lampirkan dalam file PDF.">{{ old('dokumen_diunggah') }}</textarea>
                                    @error('dokumen_diunggah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- 7. Keterangan Tambahan --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Keterangan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <textarea name="keterangan_tambahan" class="form-control @error('keterangan_tambahan') is-invalid @enderror"
                                              rows="3" placeholder="Tuliskan informasi tambahan apabila diperlukan.">{{ old('keterangan_tambahan') }}</textarea>
                                    @error('keterangan_tambahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr class="my-5 border-light">
                            <h5 class="fw-bold mb-4" style="color: var(--psdkp-blue);"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Dokumen</h5>

                            <div class="mb-4">
                                <div class="upload-dropzone" id="dropzone">
                                    <i class="fas fa-file-pdf"></i>
                                    <h5 class="fw-bold text-dark mb-2">Tarik & Letakkan File PDF di Sini</h5>
                                    <p class="text-muted small mb-3">atau klik untuk memilih file dari perangkat Anda (Maks. 1 GB)</p>
                                    
                                    <input type="file" name="file_dokumen" id="file_dokumen" class="d-none" accept=".pdf" required>
                                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="document.getElementById('file_dokumen').click()">
                                        Pilih File PDF
                                    </button>
                                    
                                    <div id="fileNameDisplay" class="mt-3 fw-semibold text-primary d-none">
                                        <i class="fas fa-check-circle me-1 text-success"></i> <span id="fileNameText"></span>
                                    </div>
                                </div>
                                @error('file_dokumen')
                                    <div class="text-danger small mt-2 fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info border-0 bg-info-soft d-flex align-items-start" style="border-radius: 10px;">
                                <i class="fas fa-info-circle text-info mt-1 me-3 fs-5"></i>
                                <div class="small" style="color: #0c5460;">
                                    <strong>Penting:</strong> Pastikan seluruh dokumen pendukung telah digabung menjadi satu file PDF sebelum diunggah. Periksa kembali kelengkapan dokumen agar proses verifikasi dapat berjalan dengan lancar.
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-md-row gap-3 mt-5">
                                <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger flex-grow-1" style="font-weight: 600; padding: 12px; border-radius: 10px;">
                                    <i class="fas fa-times me-2"></i> Batalkan & Keluar
                                </button>
                                <button type="submit" class="btn btn-primary-custom text-white shadow flex-grow-1" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Dokumen
                                </button>
                            </div>
                        </form>

                        <!-- Hidden Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted small pb-4 fade-in" style="animation-delay: 0.2s;">
                    &copy; {{ date('Y') }} Pangkalan Pengawasan Sumber Daya Kelautan dan Perikanan Bitung
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            
            // Check size (optional client side check, 1GB = 1073741824 bytes)
            if (file.size > 1073741824) {
                alert('Ukuran file terlalu besar. Maksimal 1 GB.');
                fileInput.val('');
                fileNameDisplay.addClass('d-none');
                return;
            }

            fileNameText.text(file.name + ' (' + formatBytes(file.size) + ')');
            fileNameDisplay.removeClass('d-none');
            dropzone.css('border-color', 'var(--psdkp-blue)');
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
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Mengunggah Dokumen...');
            submitBtn.prop('disabled', true);
        });
    });
</script>
</body>
</html>
