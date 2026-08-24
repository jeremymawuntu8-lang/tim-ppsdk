@extends('layouts.app')
@section('title', 'Edit BA Reklamasi')
@section('page-title', 'Edit BA Reklamasi')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('ba-reklamasi.update', $baReklamasi->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('ba-reklamasi._form')
    </form>
</div></div>
@endsection
