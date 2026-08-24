@extends('layouts.app')
@section('title', 'Detail BA Reklamasi')
@section('page-title', 'Detail BA Reklamasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ba-reklamasi.index') }}">BA Reklamasi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Header & Status --}}
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline text-center mb-4">
            <div class="card-body">
                <div class="bg-success-soft text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-mountain fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $baReklamasi->nomor_ba }}</h4>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> {{ $baReklamasi->tanggal_pengawasan?->format('d F Y') }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['draft'=>'secondary', 'proses'=>'warning', 'selesai'=>'success', 'tindak_lanjut'=>'danger'];
                        $color = $statusColors[$baReklamasi->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$baReklamasi->status)) }}</span>
                </div>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('ba-reklamasi.cetak', $baReklamasi->id) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print me-2"></i> Cetak BA (PDF)</a>
                    <a href="{{ route('ba-reklamasi.edit', $baReklamasi->id) }}" class="btn btn-warning text-dark fw-bold mt-2"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('ba-reklamasi.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Pelaku Usaha / PJ</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Perusahaan</span>
                        <strong class="text-end">{{ $baReklamasi->pelakuUsaha->nama_perusahaan ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penanggung Jawab</span>
                        <strong class="text-end">{{ $baReklamasi->penanggung_jawab_usaha ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">NIK</span>
                        <strong class="text-end">{{ $baReklamasi->nik_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Alamat PJ</span>
                        <strong class="text-end small">{{ $baReklamasi->alamat_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Pelaksana Reklamasi</span>
                        <strong class="text-end">{{ $baReklamasi->pelaksana_reklamasi ?? '-' }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Info --}}
    <div class="col-lg-8 col-md-7">
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Detail Pengawasan Reklamasi</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        <tr><th class="ps-4" width="35%">Jam Pengawasan (WITA)</th><td>{{ $baReklamasi->jam_wita ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Lokasi Reklamasi</th><td>{{ $baReklamasi->lokasi_reklamasi ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Titik Koordinat</th>
                            <td>
                                @if($baReklamasi->latitude && $baReklamasi->longitude)
                                    <a href="https://maps.google.com/?q={{ $baReklamasi->latitude }},{{ $baReklamasi->longitude }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $baReklamasi->latitude }}, {{ $baReklamasi->longitude }}
                                    </a>
                                @else - @endif
                            </td>
                        </tr>
                        <tr><th class="ps-4">Jenis Pemanfaatan Reklamasi</th><td>{{ $baReklamasi->jenis_pemanfaatan_reklamasi ?? '-' }}</td></tr>
                        
                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Dokumen Perizinan — KKPRL</th></tr>
                        <tr><th class="ps-4">Nomor Izin KKPRL</th><td>{{ $baReklamasi->kkprl_nomor_izin ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Terbit Izin</th><td>{{ $baReklamasi->kkprl_terbit_izin?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Pemberi Izin</th><td>{{ $baReklamasi->kkprl_pemberi_izin ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Peruntukan</th><td>{{ $baReklamasi->kkprl_peruntukan ?? '-' }}</td></tr>
                        
                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Izin Pelaksanaan Reklamasi</th></tr>
                        <tr><th class="ps-4">Nomor Izin</th><td>{{ $baReklamasi->izin_reklamasi_nomor ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Terbit Izin</th><td>{{ $baReklamasi->izin_reklamasi_terbit?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Pemberi Izin</th><td>{{ $baReklamasi->izin_reklamasi_pemberi ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Peruntukan</th><td>{{ $baReklamasi->izin_reklamasi_peruntukan ?? '-' }}</td></tr>

                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Izin Lainnya</th></tr>
                        <tr><th class="ps-4">Nomor Izin</th><td>{{ $baReklamasi->izin_lainnya_nomor ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Terbit Izin</th><td>{{ $baReklamasi->izin_lainnya_terbit?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Pemberi Izin</th><td>{{ $baReklamasi->izin_lainnya_pemberi ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Peruntukan</th><td>{{ $baReklamasi->izin_lainnya_peruntukan ?? '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tim Pengawas --}}
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.2s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-users me-2 text-primary"></i>Tim Pengawas</h3></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($baReklamasi->pengawas as $pg)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold text-dark">{{ $pg->nama }}</h6>
                                <span class="badge bg-secondary-soft text-secondary">Pengawas</span>
                            </div>
                            <p class="mb-0 text-muted small">{{ $pg->nip ?? '-' }} — {{ $pg->jabatan ?? '-' }}</p>
                        </div>
                    @empty
                        <div class="list-group-item text-muted text-center py-3 small">Tidak ada data pengawas</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Foto Dokumentasi --}}
        @if($baReklamasi->fotos->count() > 0)
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.3s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($baReklamasi->fotos as $foto)
                        <div class="col-md-4 col-6">
                            <a href="{{ asset('storage/'.$foto->path_foto) }}" target="_blank">
                                <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm img-hover-zoom">
                                    <img src="{{ asset('storage/'.$foto->path_foto) }}" class="object-fit-cover" alt="Foto">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.img-hover-zoom img { transition: transform .3s ease; }
.img-hover-zoom:hover img { transform: scale(1.05); }
</style>
@endpush
@endsection
