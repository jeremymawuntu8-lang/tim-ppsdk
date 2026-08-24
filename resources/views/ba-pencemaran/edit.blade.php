@extends('layouts.app')
@section('title', 'Edit BA Pencemaran')
@section('page-title', 'Edit BA Pencemaran')
@section('breadcrumb')
<li class="breadcrumb-item">Pengawasan</li>
<li class="breadcrumb-item"><a href="{{ route('ba-pencemaran.index') }}">BA Pencemaran</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header border-bottom">
                <h3 class="card-title mb-0"><i class="fas fa-edit me-2"></i>Edit Form BA Pencemaran</h3>
            </div>
            
            <form action="{{ route('ba-pencemaran.update', $baPencemaran->id) }}" method="POST" enctype="multipart/form-data" id="formBaPencemaran">
                @method('PUT')
                @include('ba-pencemaran._form')
            </form>
        </div>
    </div>
</div>
@endsection
