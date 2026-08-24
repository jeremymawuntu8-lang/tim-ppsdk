@extends('layouts.app')
@section('title', 'Edit BA WAS ALSE')
@section('page-title', 'Edit BA WAS ALSE')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('ba-was-alse.update', $baWasAlse->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('ba-was-alse._form')
    </form>
</div></div>
@endsection
