@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb')<li class="breadcrumb-item active">Profil</li>@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Avatar & Info --}}
    <div class="col-lg-4">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body text-center py-4">
                <div class="position-relative d-inline-block mb-3">
                    <img src="{{ $user->foto_profil ? asset('storage/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0A3D6B&color=fff&size=120' }}" 
                         class="rounded-circle img-thumbnail shadow-sm" alt="User Image" style="width: 120px; height: 120px; object-fit: cover; border-width: 3px;">
                </div>
                <h4 class="mb-1 fw-bold text-dark">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->jabatan ?: 'Pengguna Sistem' }}</p>
                <div class="badge bg-primary-soft text-primary px-3 py-1 rounded-pill">{{ ucfirst(str_replace('_', ' ', $user->roles->first()->name ?? 'Tanpa Role')) }}</div>
            </div>
        </div>

        <div class="card card-primary card-outline" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-lock me-2 text-primary"></i>Ubah Password</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.password') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required placeholder="Password">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary"><i class="fas fa-save me-2"></i> Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- KANAN: Form Profil --}}
    <div class="col-lg-8">
        <div class="card card-primary card-outline" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-user-edit me-2 text-primary"></i>Edit Informasi Profil</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nomor HP / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $user->jabatan }}">
                        </div>
                        <div class="col-12 mt-4">
                            <label class="form-label">Ubah Foto Profil</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="file-upload-wrapper w-100">
                                    <input type="file" name="foto_profil" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                    <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB. Resolusi kotak direkomendasikan.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan Perubahan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
