@extends('layouts.app')
@section('title', 'Detail BA WAS ALSE')
@section('page-title', 'Detail BA WAS ALSE')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ba-was-alse.index') }}">BA WAS ALSE</a></li>
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
                <h4 class="fw-bold text-dark mb-1">{{ $baWasAlse->nomor_ba }}</h4>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> {{ $baWasAlse->tanggal_pengawasan?->format('d F Y') }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['draft'=>'secondary', 'proses'=>'warning', 'selesai'=>'success', 'tindak_lanjut'=>'danger'];
                        $color = $statusColors[$baWasAlse->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$baWasAlse->status)) }}</span>
                </div>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('ba-was-alse.cetak', $baWasAlse->id) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print me-2"></i> Cetak BA (PDF)</a>
                    @if($baWasAlse->file_ba_pdf)
                        <a href="{{ asset('storage/'.$baWasAlse->file_ba_pdf) }}" target="_blank" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-2"></i> Lihat Dokumen Scan</a>
                    @endif
                    <a href="{{ route('ba-was-alse.edit', $baWasAlse->id) }}" class="btn btn-warning text-dark fw-bold mt-2"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('ba-was-alse.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Pelaku Usaha Terkait</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Perusahaan</span>
                        <strong class="text-end">{{ $baWasAlse->nama_pelaku_usaha_cetak }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penanggung Jawab</span>
                        <strong class="text-end">{{ $baWasAlse->penanggung_jawab_usaha ?? '-' }}<br><small class="text-muted fw-normal">{{ $baWasAlse->jabatan_pj_usaha ?? '' }}</small></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">No. Identitas (NIK)</span>
                        <strong class="text-end">{{ $baWasAlse->no_identitas ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="text-muted small mb-1">Alamat Perusahaan:</div>
                        <div class="fw-semibold small">{{ $baWasAlse->alamat_kantor_cetak }}</div>
                    </li>
                    <li class="list-group-item">
                        <div class="text-muted small mb-1">Alamat Kegiatan:</div>
                        <div class="fw-semibold small">{{ $baWasAlse->alamat_kegiatan ?: '-' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Info --}}
    <div class="col-lg-8 col-md-7">
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Hasil & Uraian Pengawasan ALSE</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        <tr><th class="ps-4" width="35%">Jam Pengawasan (WITA)</th><td>{{ $baWasAlse->jam_wita ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Lokasi Pengawasan</th><td>{{ $baWasAlse->lokasi ?? '-' }}, {{ $baWasAlse->provinsi_cetak }}</td></tr>
                        <tr><th class="ps-4">Titik Koordinat</th><td>{{ $baWasAlse->titik_koordinat ?? '-' }} <br><small class="text-muted">{{ $baWasAlse->latitude ?? '' }}, {{ $baWasAlse->longitude ?? '' }}</small></td></tr>
                        <tr><th class="ps-4">Kategori Pengawasan</th><td>{{ $baWasAlse->kategori_pengawasan ?? 'Pengawasan Pemanfaatan Air Laut Selain Energi' }}</td></tr>
                        <tr><th class="ps-4">Objek Pengawasan</th><td>{{ $baWasAlse->objek_pengawasan ?? 'Sarana Penampungan, Penjernihan dan Penyaluran Air Laut' }}</td></tr>
                        
                        {{-- Perizinan --}}
                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Dokumen Perizinan &amp; Kawasan</th></tr>
                        <tr><th class="ps-4">Nomor NIB</th><td>{{ $baWasAlse->nomor_nib ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Jenis Kegiatan Usaha</th><td>{{ $baWasAlse->jenis_kegiatan_usaha ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Penerbit Izin</th><td>{{ $baWasAlse->penerbit_izin ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Nomor &amp; Tgl Izin ALSE</th><td>{{ $baWasAlse->nomor_izin_alse ?? '-' }} <br><small class="text-muted">{{ $baWasAlse->tgl_terbit_izin_alse ? $baWasAlse->tgl_terbit_izin_alse->format('d M Y') : '-' }}</small></td></tr>
                        <tr><th class="ps-4">Masa Berlaku</th><td>{{ $baWasAlse->masa_berlaku_izin_alse ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Dokumen Lain</th><td>{{ $baWasAlse->nama_dokumen_lain ?? '-' }} (No: {{ $baWasAlse->nomor_dokumen_lain ?? '-' }})</td></tr>
                        <tr><th class="ps-4">Kategori Kawasan</th><td>{{ $baWasAlse->kategori_kawasan ?? '-' }}</td></tr>

                        {{-- Pemenuhan Ketentuan --}}
                        <tr class="table-info"><th colspan="2" class="ps-4 fw-bold">Pemenuhan Ketentuan</th></tr>
                        <tr><th class="ps-4">Sub-Judul</th><td>{{ $baWasAlse->judul_pemenuhan_ketentuan ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Debit Volume Air Laut</th><td>{{ $baWasAlse->debit_volume_air_laut ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Kesesuaian Volume Air</th><td>{{ $baWasAlse->kesesuaian_volume_air ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Kesesuaian Koordinat Inlet</th><td>{{ $baWasAlse->kesesuaian_koordinat_inlet ?? '-' }}</td></tr>
                    </tbody>
                </table>
                <div class="p-4 border-top bg-light">
                    <h6 class="fw-bold text-dark"><i class="fas fa-exclamation-triangle text-warning me-1"></i>Dugaan Pelanggaran: {{ $baWasAlse->dugaan_pelanggaran ?? 'Tidak Ada' }}</h6>
                    <p class="mb-3 text-muted">{{ $baWasAlse->penjelasan_dugaan_pelanggaran ?: '-' }}</p>

                    <h6 class="fw-bold text-dark"><i class="fas fa-chart-line text-primary me-1"></i>Analisa Pengawasan:</h6>
                    <p class="mb-3 text-muted">{!! nl2br(e($baWasAlse->analisa_pengawasan ?: '-')) !!}</p>
                    
                    <h6 class="fw-bold text-dark"><i class="fas fa-bullhorn text-success me-1"></i>Rekomendasi / Tindak Lanjut:</h6>
                    <p class="mb-0 text-muted">{!! nl2br(e($baWasAlse->rekomendasi ?: '-')) !!}</p>
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
                            <div class="small text-muted mb-1">No. Surat Tugas: <strong>{{ $baWasAlse->no_surat_tugas ?? '-' }}</strong></div>
                            <div class="small text-muted">Unit Kerja: <strong>{{ $baWasAlse->unit_kerja ?? 'Pangkalan PSDKP Bitung' }}</strong></div>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $baWasAlse->ketua_tim_nama ?? '-' }}</h6>
                                    <span class="badge bg-primary-soft text-primary">Ketua Tim</span>
                                </div>
                                <p class="mb-0 text-muted small">{{ $baWasAlse->ketua_tim_nip ?? '-' }} — {{ $baWasAlse->ketua_tim_jabatan ?? '-' }}</p>
                            </div>
                            @forelse($baWasAlse->pengawas as $pg)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $pg->nama }}</h6>
                                        <span class="badge bg-secondary-soft text-secondary">Anggota</span>
                                    </div>
                                    <p class="mb-0 text-muted small">{{ $pg->nip ?? '-' }} — {{ $pg->jabatan ?? '-' }}</p>
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
                            @forelse($baWasAlse->saksis as $sk)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $sk->nama }}</h6>
                                        <span class="badge bg-info-soft text-info">Saksi</span>
                                    </div>
                                    <p class="mb-0 text-muted small"><i class="fas fa-briefcase me-1"></i> {{ $sk->pekerjaan ?? '-' }}</p>
                                    <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $sk->alamat ?? '-' }}</p>
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
        @if($baWasAlse->fotos->count() > 0)
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.3s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi Pengawasan</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($baWasAlse->fotos as $foto)
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
