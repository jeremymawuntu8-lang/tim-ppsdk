@extends('layouts.app')

@section('title', 'Detail Pengawasan Tidak Langsung')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-file-lines me-2"></i>Detail Pengawasan Tidak Langsung
                    </h5>
                    <div>
                        <a href="{{ route('pengawasan-tidak-langsung.edit', $pengawasanTidakLangsung->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('pengawasan-tidak-langsung.cetak', $pengawasanTidakLangsung->id) }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak PDF
                        </a>
                        <a href="{{ route('pengawasan-tidak-langsung.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Informasi Umum</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="200">Nomor Form</th>
                            <td width="20">:</td>
                            <td>{{ $pengawasanTidakLangsung->nomor ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Unit Kerja</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->nama_unit_kerja ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pelaku Usaha</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->pelakuUsaha->nama_perusahaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->lokasi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kegiatan Usaha</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->kegiatan_usaha ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pelapor/Sumber</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->pelapor_sumber ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Laporan</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->tanggal_laporan ? $pengawasanTidakLangsung->tanggal_laporan->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Telaah</th>
                            <td>:</td>
                            <td>{{ $pengawasanTidakLangsung->tanggal_telaah ? $pengawasanTidakLangsung->tanggal_telaah->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $pengawasanTidakLangsung->status == 'selesai' ? 'success' : ($pengawasanTidakLangsung->status == 'proses' ? 'warning' : 'danger') }}">
                                    {{ ucwords(str_replace('_', ' ', $pengawasanTidakLangsung->status)) }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary mt-4">Gambar</h5>
                    <div class="row">
                        @php
                            $labels = [
                                1 => 'Pengamatan Lokasi berdasarkan Citra Google Earth',
                                2 => 'Pengamatan Lokasi berdasarkan Citra Google Earth yang berisi time series pelaksanaan kegiatan yang terindikasi melakukan pelanggaran pamanfaatan ruang laut',
                                3 => 'Overlay Basemap dengan Garis Pantai sesuai dengan peraturan perundangan yang berlaku',
                                4 => 'Menggunakan citra sentinel yang terbaru dengan kondisi citra yang terbaik',
                                5 => 'Delineasi area indikasi pelanggaran pemanfaatan ruang laut',
                                6 => 'Overlay dengan peta sebaran ekosistem laut berdasarkan situs Allen Coral Atlas atau situs yang lainnya',
                                7 => 'Overlay dengan RTR/RZWP-3-K',
                                8 => 'Overlay dengan perizinan yang ada'
                            ];
                        @endphp
                        
                        @foreach($pengawasanTidakLangsung->gambars as $gambar)
                            <div class="col-md-6 mb-4">
                                <h6>{{ $gambar->kategori }}. {{ $labels[$gambar->kategori] ?? 'Gambar' }}</h6>
                                <img src="{{ asset('storage/' . $gambar->path_file) }}" alt="Gambar" class="img-fluid rounded img-thumbnail" style="max-height: 250px;">
                            </div>
                        @endforeach
                        
                        @if($pengawasanTidakLangsung->gambars->count() == 0)
                            <div class="col-12"><p class="text-muted">Tidak ada gambar yang diunggah.</p></div>
                        @endif
                    </div>

                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary mt-4">Hasil Telaah</h5>
                    <h6><strong>a. Penjelasan dari gambar</strong></h6>
                    <div class="p-3 bg-light rounded mb-3">
                        {!! $pengawasanTidakLangsung->hasil_telaah_penjelasan ?: '<span class="text-muted">Tidak ada penjelasan.</span>' !!}
                    </div>

                    <h6><strong>b. Kesesuaian</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center align-middle">Alokasi Ruang</th>
                                    <th class="text-center align-middle">Kegiatan</th>
                                    <th class="text-center align-middle">Kesesuaian</th>
                                    <th class="text-center align-middle">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengawasanTidakLangsung->kesesuaian as $item)
                                    <tr>
                                        <td>{{ $item->alokasi_ruang }}</td>
                                        <td>{{ $item->kegiatan }}</td>
                                        <td class="text-center">
                                            @if($item->kesesuaian == 'diperbolehkan')
                                                <span class="badge bg-success">Diperbolehkan</span>
                                            @elseif($item->kesesuaian == 'tidak_diperbolehkan')
                                                <span class="badge bg-danger">Tidak Diperbolehkan</span>
                                            @elseif($item->kesesuaian == 'diperbolehkan_dengan_izin')
                                                <span class="badge bg-warning">Diperbolehkan dengan Izin</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data kesesuaian.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary mt-4">Rekomendasi</h5>
                    <div class="p-3 bg-light rounded mb-3">
                        {!! $pengawasanTidakLangsung->rekomendasi ?: '<span class="text-muted">Tidak ada rekomendasi.</span>' !!}
                    </div>

                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary mt-4">Pengesahan</h5>
                    <div class="row mt-3">
                        <div class="col-md-6 offset-md-6 text-center">
                            <p class="mb-5"><strong>Kepala UPT PSDKP,</strong></p>
                            @if($pengawasanTidakLangsung->kepala_upt_ttd)
                                <img src="{{ asset('storage/' . $pengawasanTidakLangsung->kepala_upt_ttd) }}" alt="Tanda Tangan" style="max-height: 100px;" class="mb-2">
                            @else
                                <div style="height: 100px;"></div>
                            @endif
                            <p class="mb-0 text-decoration-underline fw-bold">{{ $pengawasanTidakLangsung->kepala_upt_nama ?: '(.....................................)' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
