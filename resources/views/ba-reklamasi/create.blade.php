@extends('layouts.app')
@section('title', 'Tambah BA Reklamasi')
@section('page-title', 'Buat BA Reklamasi')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('ba-reklamasi.store') }}" enctype="multipart/form-data">
        @include('ba-reklamasi._form')
    </form>
</div></div>
@endsection
