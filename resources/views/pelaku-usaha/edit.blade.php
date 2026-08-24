@extends('layouts.app')
@section('title', 'Edit Pelaku Usaha')
@section('page-title', 'Edit Pelaku Usaha')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelaku-usaha.index') }}">Pelaku Usaha</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route('pelaku-usaha.update', $pelakuUsaha->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('pelaku-usaha._form')
        </form>
    </div>
</div>
@endsection
