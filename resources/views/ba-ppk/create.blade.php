@extends('layouts.app')
@section('title', 'Buat BA PPK')
@section('page-title', 'Buat BA PPK')
@section('breadcrumb')
<li class="breadcrumb-item">Pengawasan</li>
<li class="breadcrumb-item"><a href="{{ route('ba-ppk.index') }}">BA PPK</a></li>
<li class="breadcrumb-item active">Buat Baru</li>
@endsection
@section('content')
<form action="{{ route('ba-ppk.store') }}" method="POST" enctype="multipart/form-data" id="formBaPpk">
    @csrf
    @include('ba-ppk._form')
</form>
@endsection
