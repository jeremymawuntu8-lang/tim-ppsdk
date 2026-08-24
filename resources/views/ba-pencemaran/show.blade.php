@extends('layouts.app')
@section('title', 'Detail BA Pencemaran')
@section('page-title', 'Detail BA Pencemaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ba-pencemaran.index') }}">BA Pencemaran</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Header & Status --}}
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline text-center mb-4">
            <div class="card-body">
                <div class="bg-danger-soft text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-biohazard fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $baPencemaran->nomor_ba }}</h4>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-1"></i> {{ $baPencemaran->tanggal_pengawasan?->format('d F Y') }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['draft'=>'secondary', 'proses'=>'warning', 'selesai'=>'success', 'tindak_lanjut'=>'danger'];
                        $color = $statusColors[$baPencemaran->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$baPencemaran->status)) }}</span>
                </div>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('ba-pencemaran.cetak', $baPencemaran->id) }}" target="_blank" class="btn btn-secondary"><i class="fas fa-print me-2"></i> Cetak BA (PDF)</a>
                    <a href="{{ route('ba-pencemaran.edit', $baPencemaran->id) }}" class="btn btn-warning text-dark fw-bold mt-2"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('ba-pencemaran.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.1s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-building me-2 text-primary"></i>Pelaku Usaha / PJ</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Perusahaan</span>
                        <strong class="text-end">{{ $baPencemaran->pelakuUsaha->nama_perusahaan ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Nama Usaha/Kegiatan</span>
                        <strong class="text-end">{{ $baPencemaran->nama_usaha_kegiatan ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penanggung Jawab</span>
                        <strong class="text-end">{{ $baPencemaran->nama_pj ?? '-' }}<br><small class="text-muted fw-normal">{{ $baPencemaran->jabatan_pj ?? '' }}</small></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">NIK</span>
                        <strong class="text-end">{{ $baPencemaran->nik_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="text-muted small mb-1">Alamat Kantor:</div>
                        <div class="fw-semibold small">{{ $baPencemaran->alamat_kantor ?? '-' }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Email / Telp</span>
                        <strong class="text-end small">{{ $baPencemaran->email_pj ?? '-' }}<br>{{ $baPencemaran->no_telp_pj ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">NIB</span>
                        <strong class="text-end">{{ $baPencemaran->nib ?? '-' }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Info --}}
    <div class="col-lg-8 col-md-7">
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.15s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Detail Pengawasan Pencemaran</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <tbody>
                        <tr><th class="ps-4" width="35%">Unit Kerja</th><td>{{ $baPencemaran->unit_kerja ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Jam (WITA)</th><td>{{ $baPencemaran->jam_wita ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Lokasi Pengawasan</th><td>{{ $baPencemaran->lokasi_pengawasan ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Titik Koordinat</th>
                            <td>
                                @if($baPencemaran->latitude && $baPencemaran->longitude)
                                    <a href="https://maps.google.com/?q={{ $baPencemaran->latitude }},{{ $baPencemaran->longitude }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $baPencemaran->latitude }}, {{ $baPencemaran->longitude }}
                                    </a>
                                @else - @endif
                            </td>
                        </tr>
                        <tr><th class="ps-4">Jenis Pengawasan</th><td>{{ ucwords($baPencemaran->jenis_pengawasan ?? '-') }}</td></tr>
                        <tr><th class="ps-4">Luas Darat</th><td>{{ $baPencemaran->luas_darat ? $baPencemaran->luas_darat . ' Ha' : '-' }}</td></tr>
                        <tr><th class="ps-4">Luas Laut</th><td>{{ $baPencemaran->luas_laut ? $baPencemaran->luas_laut . ' Ha' : '-' }}</td></tr>
                        <tr><th class="ps-4">Zona / Sub Zona</th><td>{{ $baPencemaran->zona_sub_zona ?? '-' }}</td></tr>

                        @if($baPencemaran->laporan_pengaduan_nomor || $baPencemaran->laporan_pengaduan_tgl)
                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">Laporan Pengaduan Masyarakat</th></tr>
                        <tr><th class="ps-4">Nomor</th><td>{{ $baPencemaran->laporan_pengaduan_nomor ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Tanggal</th><td>{{ $baPencemaran->laporan_pengaduan_tgl?->format('d M Y') ?? '-' }}</td></tr>
                        @endif

                        <tr class="table-danger"><th colspan="2" class="ps-4 fw-bold">F. Dugaan Pencemaran</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPencemaran->dugaan_pencemaran_ada ? '<span class="badge bg-danger">Ada Dugaan</span>' : '<span class="badge bg-success">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Deskripsi</th><td>{{ $baPencemaran->dugaan_pencemaran_ket ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Luas Area Tercemar</th><td>{{ $baPencemaran->luas_area_tercemar ? $baPencemaran->luas_area_tercemar . ' Ha' : '-' }}</td></tr>

                        <tr class="table-warning"><th colspan="2" class="ps-4 fw-bold">Dampak terhadap Ekosistem</th></tr>
                        <tr><th class="ps-4">Mangrove</th><td>{{ $baPencemaran->luas_mangrove ? $baPencemaran->luas_mangrove . ' Ha' : '-' }}</td></tr>
                        <tr><th class="ps-4">Padang Lamun</th><td>{{ $baPencemaran->luas_lamun ? $baPencemaran->luas_lamun . ' Ha' : '-' }}</td></tr>
                        <tr><th class="ps-4">Terumbu Karang</th><td>{{ $baPencemaran->luas_terumbu_karang ? $baPencemaran->luas_terumbu_karang . ' Ha' : '-' }}</td></tr>
                        <tr><th class="ps-4">Habitat Populasi Ikan</th><td>{{ $baPencemaran->luas_habitat_ikan ? $baPencemaran->luas_habitat_ikan . ' Ha' : '-' }}</td></tr>

                        <tr class="table-info"><th colspan="2" class="ps-4 fw-bold">G. Pengambilan Sampel</th></tr>
                        <tr><th class="ps-4">Status</th><td>{!! $baPencemaran->sampel_ada ? '<span class="badge bg-primary">Ada</span>' : '<span class="badge bg-secondary">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Tanggal Pengambilan</th><td>{{ $baPencemaran->sampel_tgl?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Jumlah Titik & Koordinat</th><td>{{ $baPencemaran->sampel_jumlah_titik ?? '-' }} Titik — {{ $baPencemaran->sampel_koordinat ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Laboratorium</th><td>{{ $baPencemaran->sampel_nama_lab ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Tanggal Hasil Uji</th><td>{{ $baPencemaran->sampel_lab_tgl?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="ps-4">Hasil Uji</th><td>{{ ucwords(str_replace('_', ' ', $baPencemaran->sampel_hasil_uji ?? '-')) }}</td></tr>

                        <tr class="table-primary"><th colspan="2" class="ps-4 fw-bold">I. Kesimpulan Akhir</th></tr>
                        <tr><th class="ps-4">Kesesuaian Dokumen</th><td>{{ ucwords(str_replace('_', ' ', $baPencemaran->kesimpulan_dokumen ?? '-')) }}</td></tr>
                        <tr><th class="ps-4">Indikasi Pencemaran</th><td>{!! $baPencemaran->kesimpulan_indikasi_pencemaran ? '<span class="badge bg-danger">Ada</span>' : '<span class="badge bg-success">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Indikasi Pelanggaran</th><td>{!! $baPencemaran->kesimpulan_indikasi_pelanggaran ? '<span class="badge bg-danger">Ada</span>' : '<span class="badge bg-success">Tidak Ada</span>' !!}</td></tr>
                        <tr><th class="ps-4">Keterangan</th><td>{{ $baPencemaran->kesimpulan_keterangan ?? '-' }}</td></tr>
                    </tbody>
                </table>
                @if($baPencemaran->kronologis)
                <div class="p-4 border-top bg-light">
                    <h6 class="fw-bold text-dark"><i class="fas fa-align-left text-primary me-1"></i>H. Kronologis:</h6>
                    <p class="mb-0 text-muted">{!! nl2br(e($baPencemaran->kronologis)) !!}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tim Pengawas --}}
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.2s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-users me-2 text-primary"></i>Tim Pengawas</h3></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($baPencemaran->pengawas as $pg)
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
        @if($baPencemaran->fotos->count() > 0)
        <div class="card card-primary card-outline mb-4" style="animation-delay: 0.3s">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($baPencemaran->fotos as $foto)
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
