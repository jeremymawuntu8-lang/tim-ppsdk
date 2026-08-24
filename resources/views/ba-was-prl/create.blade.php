@extends('layouts.app')
@section('title', 'Tambah BA WAS PRL')
@section('page-title', 'Buat BA WAS PRL')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('ba-was-prl.store') }}" enctype="multipart/form-data">
        @include('ba-was-prl._form')
    </form>
</div></div>
@endsection
