@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Aplikasi')
@section('breadcrumb')<li class="breadcrumb-item active">Pengaturan</li>@endsection

@section('content')
<div class="row justify-content-center fade-in">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-cog me-2 text-primary"></i>Pengaturan Sistem</h3></div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('pengaturan.update') }}">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Aplikasi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-laptop-code text-muted"></i></span>
                            <input type="text" name="nama_aplikasi" class="form-control" value="{{ $pengaturan['nama_aplikasi'] }}">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Instansi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                            <input type="text" name="nama_instansi" class="form-control" value="{{ $pengaturan['nama_instansi'] }}">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat Instansi</label>
                        <textarea name="alamat_instansi" class="form-control" rows="3">{{ $pengaturan['alamat_instansi'] }}</textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
