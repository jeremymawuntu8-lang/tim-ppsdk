@php
    // Standalone halaman sukses
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen Berhasil Dikirim | PSDKP Bitung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --psdkp-blue: #0A3D6B;
            --psdkp-blue-light: #1565C0;
            --psdkp-success: #10b981;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f4f6f9; 
            color: #334155;
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: #ffffff;
            overflow: hidden;
        }
        .success-icon-container {
            width: 100px;
            height: 100px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .success-icon {
            font-size: 3.5rem;
            color: var(--psdkp-success);
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-size: 0.9rem; color: #64748b; font-weight: 500; }
        .info-value { font-size: 1rem; color: #0f172a; font-weight: 600; text-align: right; }
        
        .btn-primary-custom {
            background: var(--psdkp-blue);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background: var(--psdkp-blue-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(10, 61, 107, 0.2);
        }
        
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
<div class="min-vh-100 d-flex flex-column py-5 px-3 align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="text-center mb-4 fade-in">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PSDKP" style="height: 60px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                </div>

                <div class="card card-custom fade-in" style="animation-delay: 0.1s;">
                    <div class="card-body p-4 p-md-5 text-center">
                        
                        <div class="success-icon-container">
                            <i class="fas fa-check-circle success-icon"></i>
                        </div>
                        
                        <h3 class="fw-bold text-dark mb-3">Dokumen Berhasil Dikirim</h3>
                        
                        <p class="text-muted mb-4" style="font-size: 1.05rem;">
                            Terima kasih. Dokumen Anda telah berhasil dikirim dan sedang menunggu proses verifikasi oleh Admin Pangkalan Pengawasan Sumber Daya Kelautan dan Perikanan Bitung.
                        </p>

                        <div class="info-box text-start">
                            <div class="info-row">
                                <span class="info-label">Nomor Pengajuan</span>
                                <span class="info-value text-primary">{{ $company->nomor_pengajuan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nama Perusahaan</span>
                                <span class="info-value">{{ $company->nama_perusahaan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tanggal Pengiriman</span>
                                <span class="info-value">{{ $company->created_at->format('d F Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="info-value text-warning"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                            </div>
                        </div>

                        <div class="alert bg-light border text-start small text-muted d-flex align-items-start mb-4" style="border-radius: 10px;">
                            <i class="fas fa-info-circle text-primary mt-1 me-3 fs-5"></i>
                            <div>
                                Apabila terdapat dokumen yang kurang atau perlu diperbaiki, Anda akan menerima notifikasi melalui sistem. Anda dapat memantau status pengajuan melalui dashboard.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="{{ route('company.dashboard') }}" class="btn btn-primary-custom text-white px-5">
                                <i class="fas fa-home me-2"></i> Ke Halaman Dashboard
                            </a>
                        </div>

                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted small pb-4 fade-in" style="animation-delay: 0.2s;">
                    &copy; {{ date('Y') }} Pangkalan Pengawasan Sumber Daya Kelautan dan Perikanan Bitung
                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
