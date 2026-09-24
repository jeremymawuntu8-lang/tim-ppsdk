@extends('layouts.app')

@section('title', 'Edit Pengawasan Tidak Langsung')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Pengawasan Tidak Langsung
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengawasan-tidak-langsung.update', $pengawasanTidakLangsung->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('pengawasan-tidak-langsung._form', ['model' => $pengawasanTidakLangsung])
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('pengawasan-tidak-langsung.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
