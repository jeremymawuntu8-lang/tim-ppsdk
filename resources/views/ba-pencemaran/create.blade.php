@extends('layouts.app')
@section('title', 'Tambah BA Pencemaran')
@section('page-title', 'Tambah BA Pencemaran')
@section('breadcrumb')
<li class="breadcrumb-item">Pengawasan</li>
<li class="breadcrumb-item"><a href="{{ route('ba-pencemaran.index') }}">BA Pencemaran</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header border-bottom">
                <h3 class="card-title mb-0"><i class="fas fa-file-medical me-2"></i>Form BA Pencemaran</h3>
            </div>
            
            <form action="{{ route('ba-pencemaran.store') }}" method="POST" enctype="multipart/form-data" id="formBaPencemaran">
                @include('ba-pencemaran._form')
            </form>
        </div>
    </div>
</div>
@endsection
