<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FORM PENGAMATAN TIDAK LANGSUNG - {{ $pengawasanTidakLangsung->nomor ?: 'Draft' }}</title>
    <style>
        @page { margin: 1.27cm 1.905cm 1.27cm 1.905cm; }
        * { box-sizing: border-box; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.35;
        }
        p { margin: 4px 0; text-align: justify; }
        table { border-collapse: collapse; width: 100%; }

        /* ===== Kop Surat (diwarisi dari prl) ===== */
        .kop-surat-table { width: 100%; margin-bottom: 0; }
        .kop-logo-cell { width: 95px; vertical-align: middle; padding-right: 5px; }
        .kop-logo-cell img { width: 90px; }
        .kop-text-cell { text-align: center; vertical-align: middle; padding: 0 5px; }
        .kop-title-blue {
            color: #0000FF;
            font-weight: bold;
            font-size: 11.5pt;
            line-height: 1.18;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
        }
        .kop-address {
            color: #000000;
            font-size: 8.5pt;
            line-height: 1.25;
            margin-top: 1px;
            font-family: Arial, sans-serif;
        }
        .kop-link { color: #0000FF; text-decoration: underline; }
        .kop-link-blue { color: #0000FF; text-decoration: underline; font-style: italic; }
        .kop-divider-thick { border-bottom: 2.5pt solid #000; margin-top: 5px; }
        .kop-divider-thin { border-bottom: 1pt solid #000; margin-top: 1.5px; margin-bottom: 14px; }

        /* ===== Judul Dokumen ===== */
        h1.doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin: 15px 0 25px;
            line-height: 1.3;
        }

        /* ===== Tabel Key-Value Header ===== */
        table.header-info { margin-bottom: 15px; font-size: 10pt; font-weight: bold; }
        table.header-info td { padding: 3px 0; vertical-align: top; }
        table.header-info td.col-label { width: 130px; }
        table.header-info td.col-sep { width: 15px; text-align: center; }

        /* ===== Section Titles ===== */
        h2.section-title { font-size: 10.5pt; font-weight: bold; font-style: italic; margin: 15px 0 5px; }

        /* ===== Tabel Informasi (A) ===== */
        table.info-table { width: 100%; border: 1px solid #ccc; font-size: 10pt; }
        table.info-table td { padding: 4px 6px; border: 1px solid #ccc; vertical-align: top; font-weight: bold; font-style: italic; }
        table.info-table td.col-label { width: 160px; }
        table.info-table td.col-sep { width: 15px; text-align: center; border-right: none; }
        table.info-table td.col-value { font-weight: normal; font-style: normal; border-left: none; }

        /* ===== Tabel Gambar (B) ===== */
        table.gambar-table { width: 100%; border: 1px solid #000; font-size: 10pt; font-weight: bold; font-style: italic; }
        table.gambar-table th, table.gambar-table td { border: 1px solid #000; padding: 4px 6px; vertical-align: top; }
        table.gambar-table th { text-align: left; }
        table.gambar-table td.col-no { width: 30px; }
        .img-wrapper { text-align: center; margin-top: 5px; }
        .img-wrapper img { max-width: 100%; max-height: 300px; }

        /* ===== Tabel Telaah (C) ===== */
        .telaah-text { margin-left: 15px; margin-bottom: 10px; text-align: justify; }
        .telaah-text p { margin-top: 0; }
        
        table.telaah-table { width: 100%; border: 1px solid #000; font-size: 10pt; font-style: italic; font-weight: bold; }
        table.telaah-table th, table.telaah-table td { border: 1px solid #000; padding: 4px 6px; vertical-align: top; text-align: center; }
        table.telaah-table tbody td { font-weight: normal; font-style: normal; text-align: left; }
        table.telaah-table tbody td.text-center { text-align: center; }

        /* ===== Tanda Tangan ===== */
        .ttd-container { width: 100%; text-align: right; margin-top: 40px; page-break-inside: avoid; }
        .ttd-box { display: inline-block; width: 300px; text-align: center; }
        .ttd-title { font-weight: bold; font-style: italic; margin-bottom: 10px; }
        .ttd-img { height: 80px; margin: 10px 0; }
        .ttd-name { border-bottom: 1px dotted #000; display: inline-block; min-width: 200px; font-weight: bold; }
    </style>
</head>
<body>

@php
    use Illuminate\Support\Facades\Storage;

    $ttdSrc = function (?string $path) {
        if (!$path) return null;
        try {
            if (!Storage::disk('public')->exists($path)) return null;
            $bin = Storage::disk('public')->get($path);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'png';
            $mime = $ext === 'jpg' ? 'jpeg' : $ext;
            return 'data:image/' . $mime . ';base64,' . base64_encode($bin);
        } catch (\Throwable $e) {
            return null;
        }
    };
    
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

@include('ba-was-prl.partials.kop-surat')

<h1 class="doc-title">FORM PENGAMATAN TIDAK LANGSUNG</h1>

<table class="header-info">
    <tr>
        <td class="col-label">Nomor</td>
        <td class="col-sep">:</td>
        <td>{{ $pengawasanTidakLangsung->nomor ?: '........................................................' }}</td>
    </tr>
    <tr>
        <td class="col-label">Nama Unit Kerja</td>
        <td class="col-sep">:</td>
        <td>{{ $pengawasanTidakLangsung->nama_unit_kerja ?: '........................................................' }}</td>
    </tr>
</table>

<h2 class="section-title">A. Informasi</h2>
<table class="info-table">
    <tr>
        <td class="col-label">Lokasi</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->lokasi ?: '' }}</td>
    </tr>
    <tr>
        <td class="col-label">Pelaku Usaha</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->pelakuUsaha->nama_perusahaan ?? '' }}</td>
    </tr>
    <tr>
        <td class="col-label">Kegiatan Usaha</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->kegiatan_usaha ?: '' }}</td>
    </tr>
    <tr>
        <td class="col-label">Pelapor/ Sumber</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->pelapor_sumber ?: '' }}</td>
    </tr>
    <tr>
        <td class="col-label">Tanggal Laporan</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->tanggal_laporan ? $pengawasanTidakLangsung->tanggal_laporan->format('d/m/Y') : '' }}</td>
    </tr>
    <tr>
        <td class="col-label">Tanggal Telaah</td>
        <td class="col-sep">:</td>
        <td class="col-value">{{ $pengawasanTidakLangsung->tanggal_telaah ? $pengawasanTidakLangsung->tanggal_telaah->format('d/m/Y') : '' }}</td>
    </tr>
</table>

<h2 class="section-title">B. Gambar</h2>
<table class="gambar-table">
    <thead>
        <tr>
            <th class="col-no">No.</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 1; $i <= 8; $i++)
            <tr>
                <td class="col-no">{{ $i === 1 ? '1. 2.' : $i }}</td>
                <td>
                    {{ $labels[$i] }}
                    @php
                        $gambar = $pengawasanTidakLangsung->gambars->where('kategori', (string)$i)->first();
                    @endphp
                    @if($gambar)
                        <div class="img-wrapper">
                            <img src="{{ $ttdSrc($gambar->path_file) }}" alt="Gambar {{ $i }}">
                        </div>
                    @endif
                </td>
            </tr>
        @endfor
    </tbody>
</table>

<h2 class="section-title">C. Hasil Telaah</h2>
<div class="telaah-text">
    <strong>a. Berupa penjelasan dari gambar</strong><br>
    {!! $pengawasanTidakLangsung->hasil_telaah_penjelasan ?: '<br>' !!}
</div>

<div class="telaah-text">
    <strong>b. Apabila Polsus PWP-3-K sudah mendapatkan data (berupa dokumen perizinan, dokumen lainnya yang relevan) maka dapat dilakukan analisis analisis terhadap kesesuaian pelaksanaan kegiatan dengan:</strong>
    <ul style="margin-top: 3px; padding-left: 20px;">
        <li>Dokumen RTR dan/atau rencana Zonasi;</li>
        <li>Ketentuan yang tercantum dalam dokumen persetujuan/konfirmasi KKPRL</li>
    </ul>
</div>

<table class="telaah-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 25px;">No.</th>
            <th rowspan="2">Alokasi<br>Ruang</th>
            <th rowspan="2">Kegiatan</th>
            <th colspan="3">Kesesuaian</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>Diperbolehkan</th>
            <th>Tidak<br>diperbolehkan</th>
            <th>Diperbolehkan<br>dengan Izin</th>
        </tr>
    </thead>
    <tbody>
        @if($pengawasanTidakLangsung->kesesuaian->count() > 0)
            @foreach($pengawasanTidakLangsung->kesesuaian as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}.</td>
                    <td>{{ $item->alokasi_ruang }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td class="text-center">{{ $item->kesesuaian == 'diperbolehkan' ? '√' : '' }}</td>
                    <td class="text-center">{{ $item->kesesuaian == 'tidak_diperbolehkan' ? '√' : '' }}</td>
                    <td class="text-center">{{ $item->kesesuaian == 'diperbolehkan_dengan_izin' ? '√' : '' }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center">1.</td>
                <td>Zona.....</td>
                <td>Reklamasi,<br>cottage,...</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">...</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<h2 class="section-title">D. Rekomendasi</h2>
<div class="telaah-text" style="font-weight: bold; font-style: italic;">
    Berisi rekomendasi berdasarkan hasil telaahan<br>
    <div style="font-weight: normal; font-style: normal; margin-top: 5px;">
        {!! $pengawasanTidakLangsung->rekomendasi ?: '..........................................................................................................................................' !!}
    </div>
</div>

<div class="ttd-container">
    <div class="ttd-box">
        <div class="ttd-title">Kepala UPT PSDKP,</div>
        @if($pengawasanTidakLangsung->kepala_upt_ttd && $ttdSrc($pengawasanTidakLangsung->kepala_upt_ttd))
            <img class="ttd-img" src="{{ $ttdSrc($pengawasanTidakLangsung->kepala_upt_ttd) }}" alt="Tanda Tangan">
        @else
            <div style="height: 100px;"></div>
        @endif
        <div class="ttd-name">{{ $pengawasanTidakLangsung->kepala_upt_nama ?: '.....................................................' }}</div>
    </div>
</div>

</body>
</html>
