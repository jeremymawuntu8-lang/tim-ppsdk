@extends('layouts.app')
@section('title', 'Edit Jadwal Pengawasan')
@section('page-title', 'Edit Jadwal Pengawasan')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Jadwal</a></li><li class="breadcrumb-item active">Edit</li>@endsection
@section('content')
<div class="card card-primary card-outline"><div class="card-body">
    <form method="POST" action="{{ route('jadwal.update', $jadwal->id) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Pelaku Usaha</label>
                <select name="pelaku_usaha_id" class="form-select select2" required>
                    <option value="">-- Pilih --</option>
                    @foreach($pelakuUsahas as $p)
                        <option value="{{ $p->id }}" @selected($jadwal->pelaku_usaha_id == $p->id)>{{ $p->nama_perusahaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Pengawasan</label>
                <select name="jenis_pengawasan" class="form-select">
                    <option value="prl" @selected($jadwal->jenis_pengawasan == 'prl')>PRL</option>
                    <option value="alse" @selected($jadwal->jenis_pengawasan == 'alse')>ALSE</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Rencana</label>
                <input type="date" name="tanggal_rencana" class="form-control" value="{{ $jadwal->tanggal_rencana->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tim Pengawas</label>
                <input type="text" name="tim_pengawas" class="form-control" value="{{ $jadwal->tim_pengawas }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['belum_dilaksanakan'=>'Belum Dilaksanakan','sedang_berjalan'=>'Sedang Berjalan','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}" @selected($jadwal->status == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control">{{ $jadwal->catatan }}</textarea>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary text-white">Simpan</button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
