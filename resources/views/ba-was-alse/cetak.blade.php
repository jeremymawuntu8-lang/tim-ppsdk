<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BA WAS ALSE - {{ $baWasAlse->nomor_ba }}</title>
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

        /* ===== Kop Surat ===== */
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
        .doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 4px 0 14px;
            line-height: 1.3;
        }

        .meta-info { width: 100%; margin-bottom: 10px; font-size: 10pt; }
        .meta-info td { padding: 2px 0; vertical-align: top; }
        .meta-info td.label { width: 140px; }
        .meta-info td.sep { width: 15px; text-align: center; }

        /* ===== Tabel Tim Pengawas ===== */
        table.table-bordered { width: 100%; margin: 8px 0 12px; font-size: 9.5pt; }
        table.table-bordered th, table.table-bordered td {
            border: 1px solid #000; padding: 5px 6px; text-align: left; vertical-align: middle;
        }
        table.table-bordered th { text-align: center; font-weight: bold; background-color: #ffffff; }
        table.table-bordered td.text-center { text-align: center; }

        /* ===== Key Value Tables ===== */
        table.kv-table { width: 100%; margin: 4px 0 8px; font-size: 10pt; }
        table.kv-table td { padding: 2px 4px 2px 0; vertical-align: top; }
        table.kv-table td.label-indent { width: 200px; padding-left: 20px; }
        table.kv-table td.label-sub { width: 180px; padding-left: 35px; }
        table.kv-table td.sep { width: 12px; text-align: center; }

        .section-title { font-weight: bold; margin-top: 10px; margin-bottom: 4px; }
        .indent-block { margin-left: 20px; }

        /* ===== Tabel Pemenuhan Ketentuan ===== */
        table.table-pemenuhan { width: 100%; margin: 6px 0 12px; font-size: 9.5pt; }
        table.table-pemenuhan th, table.table-pemenuhan td {
            border: 1px solid #000; padding: 6px 8px; vertical-align: top;
        }
        table.table-pemenuhan td.col-letter { width: 28px; text-align: center; font-weight: bold; }
        table.table-pemenuhan td.col-val { width: 220px; text-align: left; }

        /* ===== Signature Area ===== */
        table.ttd-table { width: 100%; margin-top: 30px; border-collapse: collapse; page-break-inside: avoid; }
        table.ttd-table td { width: 50%; text-align: center; vertical-align: top; padding: 0 10px; }
        .ttd-img-box { height: 60px; margin: 4px auto 2px; text-align: center; }
        .ttd-img-box img { max-height: 60px; max-width: 180px; }
        .ttd-name { text-decoration: underline; font-weight: bold; margin-top: 5px; }
        .ttd-nip { font-size: 9.5pt; }

        .footer-stamp { margin-top: 30px; font-size: 8pt; color: #444; text-align: left; page-break-inside: avoid; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

@php
    use App\Helpers\Terbilang;
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

    $renderTtd = function (?string $ttdPath, ?string $nama = null, ?string $labelSub = null, $isNik = false) use ($ttdSrc) {
        $src = $ttdSrc($ttdPath);
        $html = '<div class="ttd-img-box">';
        if ($src) {
            $html .= '<img src="' . $src . '" alt="Tanda tangan">';
        }
        $html .= '</div>';
        $html .= '<div class="ttd-name">' . e($nama ?: '..........................................') . '</div>';
        if ($labelSub) {
            $prefix = $isNik ? 'NIK. ' : 'NIP. ';
            $html .= '<div class="ttd-nip">' . $prefix . e($labelSub) . '</div>';
        }
        return $html;
    };

    $tgl = Terbilang::tanggalLengkap($baWasAlse->tanggal_pengawasan);
    $jamFormat = $baWasAlse->jam_wita ? str_replace(':', '.', substr($baWasAlse->jam_wita, 0, 5)) : '.......';

    $namaUsahaCetak = $baWasAlse->nama_pelaku_usaha_cetak;
    $alamatKantorCetak = $baWasAlse->alamat_kantor_cetak;
    $provinsiCetak = $baWasAlse->provinsi_cetak;
    $lokasiCetak = $baWasAlse->lokasi ?: '.......';

    $anggotaTim = $baWasAlse->pengawas;
@endphp

{{-- KOP SURAT --}}
    @include('ba-was-prl.partials.kop-surat')

{{-- JUDUL DOKUMEN --}}
<div class="doc-title">
    BERITA ACARA HASIL PENGAWASAN<br>
    PEMANFAATAN AIR LAUT SELAIN ENERGI
</div>

<table class="meta-info">
    <tr>
        <td class="label">Nomor Surat Tugas</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->no_surat_tugas ?: '..........................................' }}</td>
    </tr>
    <tr>
        <td class="label">Unit Kerja</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->unit_kerja ?: 'Pangkalan PSDKP Bitung' }}</td>
    </tr>
</table>

<p>
    Pada hari ini {{ $tgl['hari'] }} tanggal {{ $tgl['tanggal'] }} bulan {{ $tgl['bulan'] }} tahun {{ $tgl['tahun'] }} pukul {{ $jamFormat }} WITA di {{ $lokasiCetak }}, {{ $provinsiCetak }} kami yang bertanda tangan di bawah ini:
</p>

{{-- TABEL TIM PENGAWAS --}}
<table class="table-bordered">
    <thead>
        <tr>
            <th style="width: 35px;">No.</th>
            <th>Nama</th>
            <th style="width: 170px;">NIP</th>
            <th style="width: 130px;">Jabatan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1.</td>
            <td>{{ $baWasAlse->ketua_tim_nama ?: '-' }}</td>
            <td>{{ $baWasAlse->ketua_tim_nip ?: '-' }}</td>
            <td>{{ $baWasAlse->ketua_tim_jabatan ?: 'Ketua Tim' }}</td>
        </tr>
        @foreach($anggotaTim as $i => $pg)
        <tr>
            <td class="text-center">{{ $i + 2 }}.</td>
            <td>{{ $pg->nama }}</td>
            <td>{{ $pg->nip ?: '-' }}</td>
            <td>{{ $pg->jabatan ?: 'Anggota Tim' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TABEL KATAGORI & OBJEK --}}
<table class="table-bordered" style="margin-top: 4px;">
    <tr>
        <td style="width: 180px; font-weight: bold;">Telah melakukan</td>
        <td style="width: 15px;" class="text-center">:</td>
        <td>{{ $baWasAlse->kategori_pengawasan ?: 'Pengawasan Pemanfaatan Air Laut Selain Energi' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Objek yang diawasi</td>
        <td class="text-center">:</td>
        <td>{{ $baWasAlse->objek_pengawasan ?: 'Sarana Penampungan, Penjernihan dan Penyaluran Air Laut' }}</td>
    </tr>
</table>

<div class="section-title">Hasil Pengawasan :</div>

{{-- 1. IDENTITAS PELAKU USAHA --}}
<div class="section-title" style="margin-left: 10px;">1. Identitas pelaku usaha/pelaku kegiatan:</div>
<table class="kv-table">
    <tr>
        <td class="label-indent">a. Nama</td>
        <td class="sep">:</td>
        <td><strong>{{ $namaUsahaCetak }}</strong></td>
    </tr>
    <tr>
        <td class="label-sub">Nama Penanggung Jawab</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->penanggung_jawab_usaha ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-indent">b. No. Identitas</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->no_identitas ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-indent">c. Alamat Perusahaan</td>
        <td class="sep">:</td>
        <td>{{ $alamatKantorCetak }}</td>
    </tr>
    <tr>
        <td class="label-indent">d. Alamat Kegiatan</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->alamat_kegiatan ?: '-' }}</td>
    </tr>
</table>

{{-- 2. PERIZINAN --}}
<div class="section-title" style="margin-left: 10px;">2. Perizinan</div>
<table class="kv-table">
    <tr>
        <td class="label-indent">a. NIB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Nomor</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->nomor_nib ?: '-' }}</td>
    </tr>
    <tr>
        <td colspan="3" style="padding-left: 20px; font-weight: bold; padding-top: 4px;">c. Perizinan pemanfaatan ALSE</td>
    </tr>
    <tr>
        <td class="label-sub">- Jenis Kegiatan Usaha</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->jenis_kegiatan_usaha ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-sub">- Penerbit Izin</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->penerbit_izin ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-sub">- Nomor</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->nomor_izin_alse ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-sub">- Tanggal Terbit</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->tgl_terbit_izin_alse ? Terbilang::tanggalSingkat($baWasAlse->tgl_terbit_izin_alse) : '-' }}</td>
    </tr>
    <tr>
        <td class="label-sub">- Masa Berlaku</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->masa_berlaku_izin_alse ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-indent">d. Dokumen Lain</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->nama_dokumen_lain ?: '-' }}</td>
    </tr>
    <tr>
        <td class="label-indent"></td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->nomor_dokumen_lain ?: '-' }}</td>
    </tr>
</table>

<table class="kv-table" style="margin-top: 6px;">
    <tr>
        <td style="width: 220px; font-weight: bold;">Kategori kawasan kegiatan</td>
        <td class="sep">:</td>
        <td>{{ $baWasAlse->kategori_kawasan ?: '-' }}</td>
    </tr>
</table>

{{-- 3. PEMENUHAN KETENTUAN --}}
<div class="section-title" style="margin-left: 10px;">3. Pemenuhan Ketentuan :</div>
<div style="margin-left: 25px; margin-bottom: 6px; font-style: italic;">
    {{ $baWasAlse->judul_pemenuhan_ketentuan ?: 'Penampungan, Penjernihan dan Penyaluran Air Minum/Penampungan dan Penyaluran Air Baku' }}
</div>

<table class="table-pemenuhan">
    <tr>
        <td class="col-letter">a)</td>
        <td>
            Kesesuaian kapasitas pengambilan/pemanfaatan air laut<br>
            <span style="font-size: 9pt; color: #333;">Debit volume penggunaan air laut {{ $baWasAlse->debit_volume_air_laut ? '('.$baWasAlse->debit_volume_air_laut.')' : '' }}</span>
        </td>
        <td class="col-val">{{ $baWasAlse->kesesuaian_volume_air ?: '-' }}</td>
    </tr>
    <tr>
        <td class="col-letter">b)</td>
        <td>Kesesuaian koordinat inlet</td>
        <td class="col-val">{{ $baWasAlse->kesesuaian_koordinat_inlet ?: '-' }}</td>
    </tr>
</table>

{{-- 4. DUGAAN PELANGGARAN --}}
<div class="section-title" style="margin-left: 10px;">
    4. Dugaan Pelanggaran : {{ $baWasAlse->dugaan_pelanggaran ?: '-' }}
</div>
<div class="indent-block" style="margin-bottom: 8px;">
    {!! nl2br(e($baWasAlse->penjelasan_dugaan_pelanggaran ?: '-')) !!}
</div>

{{-- 5. ANALISA PENGAWASAN --}}
<div class="section-title" style="margin-left: 10px;">
    5. Analisa Pengawasan :
</div>
<div class="indent-block" style="margin-bottom: 8px;">
    {!! nl2br(e($baWasAlse->analisa_pengawasan ?: '-')) !!}
</div>

{{-- 6. REKOMENDASI --}}
<div class="section-title" style="margin-left: 10px;">
    6. Rekomendasi :
</div>
<div class="indent-block" style="margin-bottom: 12px;">
    {!! nl2br(e($baWasAlse->rekomendasi ?: '-')) !!}
</div>

{{-- AREA TANDA TANGAN --}}
<table class="ttd-table">
    <tr>
        <td>
            <strong>Pelaku Usaha</strong>
        </td>
        <td>
            <strong>Polsus PWP3K</strong>
        </td>
    </tr>
    <tr>
        <td>
            {!! $renderTtd($baWasAlse->pj_usaha_tanda_tangan, $baWasAlse->penanggung_jawab_usaha, $baWasAlse->no_identitas, true) !!}
        </td>
        <td>
            {!! $renderTtd($baWasAlse->ketua_tim_tanda_tangan, $baWasAlse->ketua_tim_nama, $baWasAlse->ketua_tim_nip, false) !!}
        </td>
    </tr>
</table>

<div class="footer-stamp">
    Dibuat : {{ now()->format('Y-m-d H:i:s') }}/{{ substr(md5($baWasAlse->id . $baWasAlse->updated_at), 0, 8) }}
</div>

</body>
</html>
