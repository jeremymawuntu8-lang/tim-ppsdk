@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Tambah User</li>
@endsection

@section('content')
<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row g-4 fade-in">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-id-card me-2 text-primary"></i>Informasi Personal & Akun</h3></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama lengkap user" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Alamat email" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nomor HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="no_hp" class="form-control" placeholder="Nomor telepon">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Jabatan">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ ucfirst(str_replace('_', ' ', $r->name)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-toggle-on me-2 text-primary"></i>Status Akun</h3></div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="form-check form-switch ms-2" style="transform: scale(1.3);">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                        </div>
                        <label class="form-check-label ms-3 fw-bold" for="isActive">Akun Aktif</label>
                    </div>
                    <p class="text-muted text-sm mb-0">Jika dinonaktifkan, pengguna tidak akan bisa login ke dalam sistem.</p>
                </div>
            </div>

            <div class="card" style="animation-delay: 0.15s">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan Pengguna</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
