@extends('layouts.app')
@section('title', 'Detail Pelaku Usaha')
@section('page-title', 'Detail Pelaku Usaha')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelaku-usaha.index') }}">Pelaku Usaha</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4 fade-in">
    {{-- KIRI: Foto & Status --}}
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline text-center h-100">
            <div class="card-body d-flex flex-column">
                <div class="mb-4">
                    @if($pelakuUsaha->foto_lokasi)
                        <img src="{{ asset('storage/'.$pelakuUsaha->foto_lokasi) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 250px; object-fit: cover; border: 3px solid #fff;">
                    @else
                        <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="height: 200px; border: 3px solid #fff;">
                            <div class="text-muted"><i class="fas fa-building fa-4x mb-2"></i><br>Tidak ada foto lokasi</div>
                        </div>
                    @endif
                </div>
                
                <h4 class="fw-bold text-dark mb-1">{{ $pelakuUsaha->nama_perusahaan }}</h4>
                <p class="text-muted mb-3"><i class="fas fa-tag me-1"></i> {{ $pelakuUsaha->jenisUsaha->nama ?? 'Tidak Ada Kategori' }}</p>
                
                <div class="mb-4">
                    @php
                        $statusColors = ['aktif'=>'success', 'tidak_aktif'=>'secondary', 'dalam_proses'=>'warning', 'bermasalah'=>'danger'];
                        $color = $statusColors[$pelakuUsaha->status] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill fs-6">{{ ucwords(str_replace('_',' ',$pelakuUsaha->status)) }}</span>
                </div>
                
                <hr class="mt-auto w-100">
                <div class="d-grid gap-2 mt-3 w-100">
                    <a href="{{ route('pelaku-usaha.edit', $pelakuUsaha->id) }}" class="btn btn-warning text-dark fw-bold"><i class="fas fa-pen me-2"></i> Edit Data</a>
                    <a href="{{ route('pelaku-usaha.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: Detail Informasi --}}
    <div class="col-lg-8 col-md-7">
        {{-- Info Umum --}}
        <div class="card card-primary card-outline mb-4">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Umum</h3></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr><th class="ps-4" width="30%">Nomor PKKPRL</th><td>{{ $pelakuUsaha->nomor_pkkprl ?? '-' }}</td></tr>
                            <tr><th class="ps-4">Luas PKKPRL</th><td>{{ $pelakuUsaha->luas_pkkprl ? number_format($pelakuUsaha->luas_pkkprl,2).' m²' : '-' }}</td></tr>
                            <tr><th class="ps-4">Wilayah</th>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><strong>Prov/Kab:</strong> {{ $pelakuUsaha->provinsi->nama ?? '-' }}, {{ $pelakuUsaha->kabupaten->nama ?? '-' }}</span>
                                        <span class="text-muted small"><strong>Kec/Kel:</strong> {{ $pelakuUsaha->kecamatan->nama ?? '-' }}, {{ $pelakuUsaha->kelurahan->nama ?? '-' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr><th class="ps-4">Alamat Lengkap</th><td>{{ $pelakuUsaha->alamat ?? '-' }}</td></tr>
                            <tr><th class="ps-4">Titik Koordinat</th>
                                <td>
                                    @if($pelakuUsaha->latitude && $pelakuUsaha->longitude)
                                        <a href="https://maps.google.com/?q={{ $pelakuUsaha->latitude }},{{ $pelakuUsaha->longitude }}" target="_blank" class="text-primary text-decoration-none">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $pelakuUsaha->latitude }}, {{ $pelakuUsaha->longitude }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr><th class="ps-4">Kontak Person (PIC)</th>
                                <td>
                                    <strong>{{ $pelakuUsaha->nama_pic ?? '-' }}</strong> 
                                    @if($pelakuUsaha->jabatan_pic) <span class="text-muted">({{ $pelakuUsaha->jabatan_pic }})</span> @endif
                                </td>
                            </tr>
                            <tr><th class="ps-4">Telepon / Email</th>
                                <td>
                                    <div><i class="fas fa-phone fa-fw text-muted me-1"></i> {{ $pelakuUsaha->nomor_hp ?? '-' }}</div>
                                    <div><i class="fas fa-envelope fa-fw text-muted me-1"></i> {{ $pelakuUsaha->email ?? '-' }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Dokumen --}}
            <div class="col-lg-6 col-12">
                <div class="card card-primary card-outline h-100" style="animation-delay: 0.1s">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-folder-open me-2 text-primary"></i>Dokumen Terlampir</h3></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($pelakuUsaha->dokumens as $dok)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark text-sm">{{ Str::limit($dok->jenis_dokumen, 20) }}</div>
                                            <div class="text-muted text-xs">{{ Str::limit($dok->nama_file, 25) }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('pelaku-usaha.dokumen.download', $dok->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-4 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i><br>Belum ada dokumen.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Riwayat --}}
            <div class="col-lg-6 col-12">
                <div class="card card-primary card-outline h-100" style="animation-delay: 0.15s">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-history me-2 text-primary"></i>Riwayat Pengawasan</h3></div>
                    <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            @forelse($pelakuUsaha->baWasPrls->concat($pelakuUsaha->baWasAlses)->sortByDesc('tanggal_pengawasan') as $ba)
                                @php
                                    $isPrl = isset($ba->metode_pengamatan); // or specific check
                                    $link = request()->routeIs('*prl*') ? route('ba-was-prl.show', $ba->id) : '#'; 
                                    // Actually better to check class name or relation type
                                    $tipe = str_contains($ba->nomor_ba, 'PRL') ? 'PRL' : 'ALSE';
                                @endphp
                                <li class="list-group-item py-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="fw-bold text-primary">{{ $ba->nomor_ba }}</div>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ ucwords(str_replace('_',' ',$ba->status)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center text-sm text-muted">
                                        <span><i class="far fa-calendar-alt me-1"></i>{{ $ba->tanggal_pengawasan->format('d/m/Y') }}</span>
                                        <span class="badge bg-info-soft text-info">{{ $tipe }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-check fa-2x mb-2 opacity-50"></i><br>Belum ada riwayat pengawasan.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
