@extends('layouts.app')
@section('title', 'Detail BA WAS PRL')
@section('page-title', 'Detail BA WAS PRL')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ba-was-prl.index') }}">BA WAS PRL</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Header & Status --}}
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline text-center mb-4">
            <div class="card-body">
                <div class="bg-primary-soft text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-file-signature fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $baWasPrl->nomor_ba }}</h4>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> {{ $baWasPrl->tanggal_pengawasan->format('d F Y') }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['draft'=>'secondary', 'proses'=>'warning', 'selesai'=>'success', 'tindak_lanjut'=>'danger'];
                        $color = $statusColors[$baWasPrl->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$baWasPrl->status)) }}</span>
                </div>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('ba-was-prl.cetak', $baWasPrl->id) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print me-2"></i> Cetak BA (PDF)</a>
                    @if($baWasPrl->file_ba_pdf)
                        <a href="{{ asset('storage/'.$baWasPrl->file_ba_pdf) }}" target="_blank" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-2"></i> Lihat Dokumen Scan</a>
                    @endif
                    <a href="{{ route('ba-was-prl.edit', $baWasPrl->id) }}" class="btn btn-warning text-dark fw-bold mt-2"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('ba-was-prl.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Pelaku Usaha Terkait</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Perusahaan</span>
                        <strong class="text-end">{{ $baWasPrl->pelakuUsaha->nama_perusahaan ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Override Nama Usaha</span>
                        <strong class="text-end">{{ $baWasPrl->nama_usaha ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penanggung Jawab</span>
                        <strong class="text-end">{{ $baWasPrl->penanggung_jawab_usaha ?? '-' }}<br><small class="text-muted fw-normal">{{ $baWasPrl->jabatan_pj_usaha ?? '' }}</small></strong>
                    </li>
                    @if($baWasPrl->pj_usaha_tanda_tangan)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Tanda Tangan PJ</span>
                        <span class="badge bg-success-soft text-success"><i class="fas fa-signature me-1"></i>Sudah tanda tangan</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Info --}}
    <div class="col-lg-8 col-md-7">
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Hasil & Uraian Pengawasan</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        <tr><th class="ps-4" width="35%">Jam Pengawasan (WITA)</th><td>{{ $baWasPrl->jam_wita ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Lokasi Pengawasan</th><td>{{ $baWasPrl->lokasi ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Titik Koordinat</th><td>{{ $baWasPrl->titik_koordinat ?? '-' }} <br><small class="text-muted">{{ $baWasPrl->latitude ?? '' }}, {{ $baWasPrl->longitude ?? '' }}</small></td></tr>
                        <tr><th class="ps-4">Jenis Usaha</th><td>{{ $baWasPrl->jenis_usaha_cetak ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Luas Area</th><td>{{ $baWasPrl->luas_area_cetak ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Provinsi</th><td>{{ $baWasPrl->provinsi_cetak ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Metode Pengamatan</th><td>{{ ucwords(str_replace('_',' ',$baWasPrl->metode_pengamatan ?? '-')) }}</td></tr>
                        <tr><th class="ps-4">Nomor Perda RZWP3K</th><td>{{ $baWasPrl->nomor_perda_rzwp3k ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Nomor & Tanggal PKKPRL</th><td>{{ $baWasPrl->nomor_pkkprl ?? '-' }} <br><small class="text-muted">{{ $baWasPrl->tgl_terbit_pkkprl ? $baWasPrl->tgl_terbit_pkkprl->format('d M Y') : '-' }}</small></td></tr>
                        <tr><th class="ps-4">Status Kesesuaian KKPRL</th><td>
                            @if($baWasPrl->status_kesesuaian_kkprl == 'sesuai') <span class="badge bg-success"><i class="fas fa-check me-1"></i> Sesuai</span>
                            @elseif($baWasPrl->status_kesesuaian_kkprl == 'tidak_sesuai') <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Tidak Sesuai</span>
                            @else - @endif
                        </td></tr>
                        <tr><th class="ps-4">Izin Pengelolaan</th><td>{{ $baWasPrl->izin_pengelolaan_nomor ?? '-' }}
                            @if($baWasPrl->kesesuaian_izin_pengelolaan == 'sesuai') <span class="badge bg-success ms-1"><i class="fas fa-check me-1"></i> Sesuai</span>
                            @elseif($baWasPrl->kesesuaian_izin_pengelolaan == 'tidak_sesuai') <span class="badge bg-danger ms-1"><i class="fas fa-times me-1"></i> Tidak Sesuai</span>
                            @endif
                        </td></tr>
                        <tr><th class="ps-4">Pemenuhan Kewajiban PKKPRL</th><td>{{ ucwords(str_replace('_',' ',$baWasPrl->pemenuhan_kewajiban_pkkprl ?? '-')) }}</td></tr>
                    </tbody>
                </table>
                <div class="p-4 border-top bg-light">
                    <h6 class="fw-bold text-dark">Catatan Dokumen PKKPRL:</h6>
                    <p class="mb-3 text-muted">{{ $baWasPrl->catatan_dokumen_pkkprl ?: '-' }}</p>
                    
                    <h6 class="fw-bold text-dark">Uraian Hasil Pengawasan:</h6>
                    <p class="mb-3 text-muted">{{ $baWasPrl->hasil_pengawasan ?: '-' }}</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="alert alert-success h-100 mb-0">
                                <h6 class="fw-bold text-success mb-2"><i class="fas fa-check-circle me-1"></i>Kesimpulan</h6>
                                <p class="mb-0 small">{{ $baWasPrl->kesimpulan ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info h-100 mb-0">
                                <h6 class="fw-bold text-info mb-2"><i class="fas fa-info-circle me-1"></i>Rekomendasi</h6>
                                <p class="mb-0 small">{{ $baWasPrl->rekomendasi ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Tim Pengawas --}}
            <div class="col-lg-6 col-12">
                <div class="card card-primary card-outline h-100" style="animation-delay: 0.2s">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-users me-2 text-primary"></i>Tim Pengawas</h3></div>
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom bg-light">
                            <div class="small text-muted mb-1">No. Surat Tugas: <strong>{{ $baWasPrl->no_surat_tugas ?? '-' }}</strong></div>
                            <div class="small text-muted">Unit Kerja: <strong>{{ $baWasPrl->ketua_tim_unit_kerja ?? '-' }}</strong></div>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $baWasPrl->ketua_tim_nama ?? '-' }}</h6>
                                    <span class="badge bg-primary-soft text-primary">Ketua Tim</span>
                                </div>
                                <p class="mb-0 text-muted small">{{ $baWasPrl->ketua_tim_nip ?? '-' }} — {{ $baWasPrl->ketua_tim_jabatan ?? '-' }} — {{ $baWasPrl->ketua_tim_unit_kerja ?? '-' }}</p>
                                @if($baWasPrl->ketua_tim_tanda_tangan)
                                    <span class="badge bg-success-soft text-success mt-1"><i class="fas fa-signature me-1"></i>Sudah tanda tangan</span>
                                @endif
                            </div>
                            @forelse($baWasPrl->pengawas as $pg)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $pg->nama }}</h6>
                                        <span class="badge bg-secondary-soft text-secondary">Anggota</span>
                                    </div>
                                    <p class="mb-0 text-muted small">{{ $pg->nip ?? '-' }} — {{ $pg->jabatan ?? '-' }} — {{ $pg->unit_kerja ?? '-' }}</p>
                                    @if($pg->tanda_tangan)
                                        <span class="badge bg-success-soft text-success mt-1"><i class="fas fa-signature me-1"></i>Sudah tanda tangan</span>
                                    @endif
                                </div>
                            @empty
                                <div class="list-group-item text-muted text-center py-3 small">Tidak ada anggota pengawas</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Saksi --}}
            <div class="col-lg-6 col-12">
                <div class="card card-primary card-outline h-100" style="animation-delay: 0.25s">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-handshake me-2 text-primary"></i>Saksi-saksi</h3></div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($baWasPrl->saksis as $sk)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $sk->nama }}</h6>
                                        <span class="badge bg-info-soft text-info">Saksi</span>
                                    </div>
                                    <p class="mb-0 text-muted small"><i class="fas fa-briefcase me-1"></i> {{ $sk->pekerjaan ?? '-' }}</p>
                                    <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $sk->alamat ?? '-' }}</p>
                                    @if($sk->tanda_tangan)
                                        <span class="badge bg-success-soft text-success mt-1"><i class="fas fa-signature me-1"></i>Sudah tanda tangan</span>
                                    @endif
                                </div>
                            @empty
                                <div class="list-group-item text-muted text-center py-4 small">
                                    <i class="fas fa-users-slash fa-2x mb-2 opacity-50"></i><br>Tidak ada saksi dicatat
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Dokumentasi --}}
        @if($baWasPrl->fotos->count() > 0)
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.3s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi Pengawasan</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($baWasPrl->fotos as $foto)
                        <div class="col-md-4 col-6">
                            <a href="{{ asset('storage/'.$foto->path_foto) }}" target="_blank">
                                <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm img-hover-zoom">
                                    <img src="{{ asset('storage/'.$foto->path_foto) }}" class="object-fit-cover" alt="Foto Dokumentasi">
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
