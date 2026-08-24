@extends('layouts.app')
@section('title', 'Detail BA PPK')
@section('page-title', 'Detail BA PPK')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ba-ppk.index') }}">BA PPK</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Header & Status --}}
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline text-center mb-4">
            <div class="card-body">
                <div class="bg-warning-soft text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-island-tropical fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $baPpk->nomor_ba }}</h4>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> {{ $baPpk->tanggal_pengawasan?->format('d F Y') }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['draft'=>'secondary', 'proses'=>'warning', 'selesai'=>'success', 'tindak_lanjut'=>'danger'];
                        $color = $statusColors[$baPpk->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$baPpk->status)) }}</span>
                </div>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('ba-ppk.cetak', $baPpk->id) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print me-2"></i> Cetak BA (PDF)</a>
                    <a href="{{ route('ba-ppk.edit', $baPpk->id) }}" class="btn btn-warning text-dark fw-bold mt-2"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('ba-ppk.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Pelaku Usaha / PJ</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Perusahaan</span>
                        <strong class="text-end">{{ $baPpk->pelakuUsaha->nama_perusahaan ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penanggung Jawab</span>
                        <strong class="text-end">{{ $baPpk->nama_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">NIK</span>
                        <strong class="text-end">{{ $baPpk->nik_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Alamat PJ</span>
                        <strong class="text-end small">{{ $baPpk->alamat_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status Modal</span>
                        <strong class="text-end">{{ ucwords(str_replace('_', ' ', $baPpk->status_modal ?? '-')) }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Info --}}
    <div class="col-lg-8 col-md-7">
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Detail Pengawasan PPK</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        <tr><th class="ps-4" width="35%">Unit Kerja</th><td>{{ $baPpk->unit_kerja ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Jam (WITA)</th><td>{{ $baPpk->jam_wita ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Lokasi</th><td>{{ $baPpk->lokasi ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Nama Pulau</th><td>{{ $baPpk->nama_pulau ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Kategori Lokasi</th><td>{{ strtoupper($baPpk->kategori_lokasi ?? '-') }}</td></tr>
                        <tr><th class="ps-4">Titik Koordinat</th>
                            <td>
                                @if($baPpk->latitude && $baPpk->longitude)
                                    <a href="https://maps.google.com/?q={{ $baPpk->latitude }},{{ $baPpk->longitude }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $baPpk->latitude }}, {{ $baPpk->longitude }}
                                    </a>
                                @else - @endif
                            </td>
                        </tr>
                        
                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Jenis Usaha / Kegiatan</th></tr>
                        <tr><td colspan="2" class="ps-4">
                            @if(is_array($baPpk->jenis_usaha) && count($baPpk->jenis_usaha) > 0)
                                @foreach($baPpk->jenis_usaha as $ju)
                                    <span class="badge bg-info me-1 mb-1">{{ $ju }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td></tr>

                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Rekomendasi PPK</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPpk->rek_ppk_ada ? '<span class="badge bg-success">Ada</span>' : '<span class="badge bg-secondary">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Nomor</th><td>{{ $baPpk->rek_ppk_nomor ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Tanggal Terbit</th><td>{{ $baPpk->rek_ppk_tgl?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Penerbit</th><td>{{ $baPpk->rek_ppk_penerbit ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Masa Berlaku</th><td>{{ $baPpk->rek_ppk_masa_berlaku ?? '-' }}</td></tr>

                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">PKKPR</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPpk->pkkpr_ada ? '<span class="badge bg-success">Ada</span>' : '<span class="badge bg-secondary">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Nomor</th><td>{{ $baPpk->pkkpr_nomor ?? '-' }}</td></tr>

                        <tr class="table-info"><th colspan="2" class="ps-4 fw-bold">Dugaan Pelanggaran</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPpk->dugaan_pelanggaran_ada ? '<span class="badge bg-danger">Ada</span>' : '<span class="badge bg-success">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Keterangan</th><td>{{ $baPpk->dugaan_pelanggaran_ket ?? '-' }}</td></tr>

                        <tr class="table-info"><th colspan="2" class="ps-4 fw-bold">Dugaan Kerusakan</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPpk->dugaan_kerusakan_ada ? '<span class="badge bg-danger">Ada</span>' : '<span class="badge bg-success">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Keterangan</th><td>{{ $baPpk->dugaan_kerusakan_ket ?? '-' }}</td></tr>
                    </tbody>
                </table>
                @if($baPpk->kronologis)
                <div class="p-4 border-top bg-light">
                    <h6 class="fw-bold text-dark"><i class="fas fa-align-left text-primary me-1"></i>Kronologis:</h6>
                    <p class="mb-0 text-muted">{!! nl2br(e($baPpk->kronologis)) !!}</p>
                </div>
                @endif
                @if($baPpk->rekomendasi)
                <div class="p-4 border-top bg-light">
                    <h6 class="fw-bold text-dark"><i class="fas fa-bullhorn text-success me-1"></i>Rekomendasi / Tindak Lanjut:</h6>
                    <p class="mb-0 text-muted">{!! nl2br(e($baPpk->rekomendasi)) !!}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tim Pengawas --}}
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.2s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-users me-2 text-primary"></i>Tim Pengawas</h3></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($baPpk->pengawas as $pg)
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

        {{-- Foto --}}
        @if($baPpk->fotos->count() > 0)
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.3s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($baPpk->fotos as $foto)
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
