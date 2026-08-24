@extends('layouts.app')
@section('title', 'Edit BA PPK')
@section('page-title', 'Edit BA PPK')
@section('breadcrumb')
<li class="breadcrumb-item">Pengawasan</li>
<li class="breadcrumb-item"><a href="{{ route('ba-ppk.index') }}">BA PPK</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<form action="{{ route('ba-ppk.update', $baPpk->id) }}" method="POST" enctype="multipart/form-data" id="formBaPpk">
    @csrf @method('PUT')
    @include('ba-ppk._form')
</form>
@endsection
