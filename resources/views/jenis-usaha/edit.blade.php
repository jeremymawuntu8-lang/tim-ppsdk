@extends('layouts.app')
@section('title', 'Edit Jenis Usaha')
@section('page-title', 'Edit Jenis Usaha')
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('jenis-usaha.update', $jenisUsaha->id) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" value="{{ $jenisUsaha->nama }}" required></div>
        <div class="mb-3"><label class="form-label">Kode</label><input type="text" name="kode" class="form-control" value="{{ $jenisUsaha->kode }}"></div>
        <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control">{{ $jenisUsaha->keterangan }}</textarea></div>
        <button class="btn btn-primary text-white">Simpan</button>
        <a href="{{ route('jenis-usaha.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
