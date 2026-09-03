@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content')
<form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf @method('PUT')
    <div class="row g-4 fade-in">
        <div class="col-lg-8">
            {{-- Alert jika super-admin mengedit diri sendiri --}}
            @if($isSelfEdit)
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-info-circle me-2 fs-5"></i>
                <div>
                    <strong>Perhatian:</strong> Sebagai Super-admin, Anda hanya dapat mengubah <strong>nama</strong> Anda sendiri.
                    Untuk mengubah data lain, hubungi administrator sistem.
                </div>
            </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-id-card me-2 text-primary"></i>Informasi Personal & Akun</h3></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" {{ $isSelfEdit ? '' : 'name=email' }} class="form-control {{ $isSelfEdit ? 'bg-light' : '' }}" value="{{ $user->email }}" {{ $isSelfEdit ? 'readonly' : 'required' }}>
                            @if($isSelfEdit)
                                <input type="hidden" name="email" value="{{ $user->email }}">
                            @endif
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Nomor HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" {{ $isSelfEdit ? '' : 'name=no_hp' }} class="form-control {{ $isSelfEdit ? 'bg-light' : '' }}" value="{{ $user->no_hp }}" {{ $isSelfEdit ? 'readonly' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jabatan</label>
                            <input type="text" {{ $isSelfEdit ? '' : 'name=jabatan' }} class="form-control {{ $isSelfEdit ? 'bg-light' : '' }}" value="{{ $user->jabatan }}" {{ $isSelfEdit ? 'readonly' : '' }}>
                        </div>
                        @if(!$isSelfEdit && $user->auth_provider !== 'google')
                        <div class="col-md-6 col-12">
                            <label class="form-label">Password <span class="text-muted text-xs">(kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}" @selected($user->hasRole($r->name))>{{ ucfirst(str_replace('_', ' ', $r->name)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            @if(!$isSelfEdit)
                            <div class="col-md-6 col-12">
                                <label class="form-label">Password <span class="text-muted text-xs">(kosongkan jika tidak diubah)</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                            @endif
                        <div class="col-md-6 col-12">
                            <label class="form-label">Role Akses</label>
                            <div class="form-control bg-light d-flex align-items-center">
                                @if($user->auth_provider === 'google')
                                    <span class="badge bg-primary me-2"><i class="fas fa-building me-1"></i>Perusahaan</span>
                                @else
                                    <span class="badge bg-danger me-2"><i class="fas fa-shield-alt me-1"></i>Super-admin</span>
                                @endif
                                <small class="text-muted">(tidak dapat diubah)</small>
                            </div>
                        </div>
                        @endif
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
                            @if($isSelfEdit)
                                <input type="checkbox" class="form-check-input" id="isActive" checked disabled>
                                <input type="hidden" name="is_active" value="1">
                            @else
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" @checked($user->is_active)>
                            @endif
                        </div>
                        <label class="form-check-label ms-3 fw-bold" for="isActive">Akun Aktif</label>
                    </div>
                    <p class="text-muted text-sm mb-0">
                        @if($isSelfEdit)
                            Akun Super-admin tidak dapat dinonaktifkan oleh diri sendiri.
                        @else
                            Jika dinonaktifkan, pengguna tidak akan bisa login ke dalam sistem.
                        @endif
                    </p>
                </div>
            </div>

            <div class="card" style="animation-delay: 0.15s">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Update Pengguna</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

