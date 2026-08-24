@extends('layouts.app')
@section('title', 'Tambah BA WAS ALSE')
@section('page-title', 'Buat BA WAS ALSE')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('ba-was-alse.store') }}" enctype="multipart/form-data">
        @include('ba-was-alse._form')
    </form>
</div></div>
@endsection
