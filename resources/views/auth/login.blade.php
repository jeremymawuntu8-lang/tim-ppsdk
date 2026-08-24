<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | TIM IPSDK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ppsdk-primary: #0A3D6B;
            --ppsdk-primary-hover: #072a4a;
        }
        body { 
            background: #f8fafc;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            font-family: 'Inter', sans-serif;
            margin: 0;
        }
        .login-card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
            overflow: hidden; 
            background: #fff;
        }
        .login-side { 
            background: linear-gradient(135deg, var(--ppsdk-primary) 0%, #1565C0 100%); 
            color: #fff; 
            padding: 4rem 3rem; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            position: relative;
        }
        .login-side::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="circles" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23circles)"/></svg>');
            pointer-events: none;
        }
        .login-side-content { position: relative; z-index: 1; }
        
        .form-control { 
            padding: 0.8rem 1rem; 
            border-radius: 8px; 
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--ppsdk-primary);
            box-shadow: 0 0 0 0.25rem rgba(10, 61, 107, 0.1);
        }
        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #94a3b8;
        }
        
        .btn-login { 
            background: var(--ppsdk-primary); 
            border-color: var(--ppsdk-primary); 
            padding: 0.8rem; 
            border-radius: 8px; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover { 
            background: var(--ppsdk-primary-hover); 
            border-color: var(--ppsdk-primary-hover); 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 61, 107, 0.2);
        }
        
        .password-toggle {
            cursor: pointer;
            background: transparent;
            border: none;
            color: #94a3b8;
        }
        .password-toggle:hover { color: var(--ppsdk-primary); }

        .fade-in { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            body { padding: 1rem; }
            .login-card { border-radius: 16px; }
            .mobile-logo { display: block !important; text-align: center; margin-bottom: 1.5rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card login-card fade-in">
                <div class="row g-0">
                    {{-- Kiri: Branding (Desktop) --}}
                    <div class="col-md-5 login-side d-none d-md-flex text-center">
                        <div class="login-side-content">
                            <div class="mb-4">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo TIM IPSDK" class="img-fluid" style="max-height: 130px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));">
                            </div>
                            <h3 class="fw-bold mb-3" style="letter-spacing: -0.02em;">TIM IPSDK</h3>
                            <p class="mb-0 opacity-75 text-sm" style="line-height: 1.6;">Sistem Informasi Pengawasan Sumber Daya Kelautan. Mengelola data pengawasan secara efisien dan terintegrasi.</p>
                        </div>
                    </div>
                    
                    {{-- Kanan: Form --}}
                    <div class="col-md-7 p-4 p-md-5 p-xl-5 d-flex flex-column justify-content-center">
                        
                        {{-- Mobile Logo --}}
                        <div class="mobile-logo d-none">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;">
                            <h4 class="fw-bold mt-2 mb-0" style="color: var(--ppsdk-primary);">TIM IPSDK</h4>
                        </div>

                        <div class="mb-4">
                            <h4 class="fw-bold mb-1" style="color: var(--ppsdk-primary);">Selamat Datang! 👋</h4>
                            <p class="text-muted">Silakan masuk menggunakan akun Anda.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger bg-danger-soft text-danger border-0 d-flex align-items-center" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success bg-success-soft text-success border-0 d-flex align-items-center" style="border-radius: 8px;">
                                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0 ps-0" required autofocus placeholder="Masukkan email">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="password" name="password" class="form-control border-start-0 border-end-0 ps-0" required placeholder="Masukkan password">
                                    <span class="input-group-text bg-white pe-3" style="border-left: none;">
                                        <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100 text-white mb-4">
                                Masuk <i class="fas fa-arrow-right ms-2"></i>
                            </button>

                            <div class="position-relative text-center mb-4">
                                <hr class="text-muted">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">Atau masuk sebagai perusahaan</span>
                            </div>

                            <a href="{{ route('auth.google') }}" class="btn w-100 fw-bold" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.8rem; background: #fff; color: #475569; transition: all 0.2s;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;">
                                Lanjutkan dengan Google
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
</script>
</body>
</html>
