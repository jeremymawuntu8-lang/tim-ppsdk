@extends('layouts.app')

@section('title', 'Detail Surat Peringatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Detail Surat Peringatan</h3>
                    <div class="card-tools">
                        <a href="{{ route('surat-peringatan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('surat-peringatan.edit', $spsatu->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit Data
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th width="30%">ID Penerbitan</th>
                            <td>{{ $spsatu->id_penerbitan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pelaku Usaha / Subjek Hukum</th>
                            <td>{{ $spsatu->pelakuUsaha->nama_perusahaan ?? ($spsatu->contact_person ?? '-') }}</td>
                        </tr>
                        <tr>
                            <th>Nomor KKPRL</th>
                            <td>{{ $spsatu->nomor_kkprl ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penerbitan</th>
                            <td>{{ $spsatu->tanggal_penerbitan ? \Carbon\Carbon::parse($spsatu->tanggal_penerbitan)->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status Akhir (Sanksi)</th>
                            <td>
                                @if($spsatu->sanksi)
                                    <span class="badge bg-danger">{{ $spsatu->sanksi }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            {{-- Arsip Dokumen Component --}}
            <x-arsip-dokumen-ba :arsipable="$spsatu" tipeBa="surat-peringatan" />
        </div>
    </div>
</div>
@endsection
