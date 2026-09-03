<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BA WAS PRL - {{ $baWasPrl->nomor_ba }}</title>
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
        h1.doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 4px 0 12px;
            line-height: 1.3;
        }

        /* ===== Tabel Tim Pengawas ===== */
        table.team-table { width: 100%; margin: 8px 0 10px; font-size: 9.5pt; }
        table.team-table th, table.team-table td {
            border: 1px solid #000; padding: 5px 6px; text-align: left; vertical-align: top;
        }
        table.team-table th { text-align: center; font-weight: bold; }
        table.team-table td.col-no { text-align: center; width: 26px; }

        ol.kegiatan-list { margin: 4px 0 10px; padding-left: 22px; }
        ol.kegiatan-list li { margin-bottom: 2px; }

        /* ===== Tabel Key-Value ===== */
        table.kv-plain { margin: 6px 0 12px; font-size: 10pt; width: 100%; }
        table.kv-plain td { padding: 2px 4px 2px 0; vertical-align: top; }
        table.kv-plain td.kv-label { width: 220px; }
        table.kv-plain td.kv-sep { width: 12px; text-align: center; }
        .kv-sub { font-size: 8.5pt; color: #333; }

        /* ===== Tabel Bernomor ===== */
        table.numbered-table { width: 100%; margin: 8px 0 10px; font-size: 9.5pt; }
        table.numbered-table > tbody > tr > td {
            border: 1px solid #000; padding: 5px 6px; vertical-align: top;
        }
        table.numbered-table td.col-no { width: 25px; text-align: center; font-weight: bold; }
        table.numbered-table td.col-label { width: 190px; }
        table.numbered-table td.col-sep { width: 12px; text-align: center; }

        table.sub-kv { width: 100%; font-size: 9.5pt; }
        table.sub-kv td { border: none !important; padding: 1px 0; }
        table.sub-kv td.sub-label { width: 120px; vertical-align: top; }
        table.sub-kv td.sub-sep { width: 10px; vertical-align: top; }

        table.sub-ab { width: 100%; font-size: 9.5pt; margin: 0; }
        table.sub-ab td, table.sub-ab th { border: 1px solid #000 !important; padding: 4px 5px; }
        table.sub-ab td.col-huruf { width: 20px; text-align: center; }

        ul.bullet-list { margin: 2px 0; padding-left: 16px; }
        ul.bullet-list li { margin-bottom: 3px; text-align: justify; }
        .catatan-block { margin-top: 4px; padding-top: 2px; font-style: italic; }

        p.penutup { margin-top: 10px; margin-bottom: 16px; }

        /* ===== Tanda Tangan ===== */
        table.ttd-table { width: 100%; margin-top: 20px; page-break-inside: avoid; }
        table.ttd-table td { width: 50%; text-align: center; vertical-align: top; padding: 0 10px; }

        p.tembusan { margin-top: 20px; font-size: 9.5pt; line-height: 1.3; page-break-inside: avoid; }
        p.dicetak-footer { margin-top: 25px; font-size: 8pt; color: #444; }

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

    $orDash = fn ($v) => ($v !== null && trim((string) $v) !== '') ? $v : '-';

    $labelSesuai = fn ($v) => match ($v) {
        'sesuai' => 'Ya, Sesuai',
        'tidak_sesuai' => 'Tidak Sesuai',
        default => '-',
    };
    $labelTerpenuhi = fn ($v) => match ($v) {
        'terpenuhi' => 'Terpenuhi',
        'tidak_terpenuhi' => 'Tidak Terpenuhi',
        default => '-',
    };
    $labelAda = fn ($v) => match ($v) {
        'ada' => 'Ada',
        'tidak_ada' => 'Tidak Ada',
        default => '-',
    };

    $tgl = Terbilang::tanggalLengkap($baWasPrl->tanggal_pengawasan);
    $jamFormat = $baWasPrl->jam_wita ? str_replace(':', '.', substr($baWasPrl->jam_wita, 0, 5)) : '-';

    $namaUsahaCetak = $baWasPrl->nama_usaha_cetak ?: '-';
    $jenisUsahaCetak = $baWasPrl->jenis_usaha_cetak ?: '-';
    $luasAreaCetak = $orDash($baWasPrl->luas_area_cetak);
    $provinsiCetak = $baWasPrl->provinsi_cetak ?: '-';
    $lokasiLengkap = trim($baWasPrl->lokasi . ' ' . $baWasPrl->titik_koordinat);

    $anggotaTim = $baWasPrl->pengawas;
    $saksiList = $baWasPrl->saksis;

    $bulletItems = [];
    if (!empty($baWasPrl->kesimpulan)) {
        foreach (preg_split('/\r\n|\r|\n/', trim($baWasPrl->kesimpulan)) as $line) {
            if (trim($line) !== '') $bulletItems[] = trim($line, " \t-•");
        }
    }
    if (!empty($baWasPrl->rekomendasi)) {
        foreach (preg_split('/\r\n|\r|\n/', trim($baWasPrl->rekomendasi)) as $line) {
            if (trim($line) !== '') $bulletItems[] = trim($line, " \t-•");
        }
    }
@endphp

{{-- ======================================================================
     DOKUMEN 1 — BERITA ACARA INSPEKSI LAPANGAN
====================================================================== --}}
<div class="doc-section">
    @include('ba-was-prl.partials.kop-surat')

    <h1 class="doc-title">Berita Acara Inspeksi Lapangan</h1>

    <p>
        Pada hari ini {{ $tgl['hari'] }} tanggal {{ $tgl['tanggal'] }} bulan {{ $tgl['bulan'] }}
        tahun {{ $tgl['tahun'] }} pukul {{ $jamFormat }} WITA bertempat di {{ $baWasPrl->lokasi }},
        kami yang bertanda tangan di bawah ini :
    </p>

    <table class="team-table">
        <thead>
            <tr>
                <th style="width:26px;">No</th>
                <th>Nama</th>
                <th style="width:120px;">NIP</th>
                <th style="width:120px;">Jabatan</th>
                <th style="width:120px;">Unit Kerja</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-no">1</td>
                <td>{{ $baWasPrl->ketua_tim_nama }}</td>
                <td>{{ $orDash($baWasPrl->ketua_tim_nip) }}</td>
                <td>{{ $baWasPrl->ketua_tim_jabatan }}</td>
                <td>{{ $baWasPrl->ketua_tim_unit_kerja }}</td>
            </tr>
            @foreach($anggotaTim as $i => $pg)
            <tr>
                <td class="col-no">{{ $i + 2 }}</td>
                <td>{{ $pg->nama }}</td>
                <td>{{ $orDash($pg->nip) }}</td>
                <td>{{ $orDash($pg->jabatan) }}</td>
                <td>{{ $pg->unit_kerja ?: $baWasPrl->ketua_tim_unit_kerja }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        Telah melakukan pemeriksaan lapangan berdasarkan Surat Tugas Kepala Pangkalan PSDKP Bitung
        nomor {{ $orDash($baWasPrl->no_surat_tugas) }}, melalui kegiatan sebagai berikut:
    </p>
    <ol class="kegiatan-list">
        <li>meminta informasi melalui wawancara</li>
        <li>meminta data, dokumen/perizinan dan berkas lainnya</li>
        <li>memeriksa kelengkapan dokumen/perizinan</li>
        <li>Melakukan Inpeksi Lapangan</li>
        <li>mendokumentasikan kegiatan inspeksi lapangan</li>
    </ol>

    <p>Pelaksanaan pemeriksaan tersebut telah diketahui dan dibenarkan oleh:</p>
    <table class="kv-plain">
        <tr>
            <td class="kv-label">Nama Unit Kegiatan/Usaha<br><span class="kv-sub">(Badan Hukum/Perorangan)</span></td>
            <td class="kv-sep">:</td>
            <td><strong>{{ $namaUsahaCetak }}</strong></td>
        </tr>
        <tr>
            <td class="kv-label">Nama Penanggung Jawab</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baWasPrl->penanggung_jawab_usaha) }}</td>
        </tr>
        <tr>
            <td class="kv-label">Jabatan</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baWasPrl->jabatan_pj_usaha) }}</td>
        </tr>
    </table>

    <p class="penutup">
        Demikian Berita Acara Pemeriksaan Lapangan ini dibuat dengan sebenar-benarnya dan mengingat Sumpah Jabatan.
    </p>

    {{-- ============================================================
         BLOK TANDA TANGAN — GABUNGAN TIM PEMERIKSA & PENANGGUNG JAWAB
         Harus dalam 1 tabel utuh agar tidak terpisah beda halaman
    ============================================================ --}}
    <table style="width:100%; margin-top:24px; margin-bottom:10px; page-break-inside:avoid;" cellpadding="0" cellspacing="0">
        {{-- BLOK 1: Tim Pemeriksa Lapangan (Anggota) --}}
        <tr>
            <td style="width:50%; text-align:center; vertical-align:top; padding:0 10px 6px 10px;">
                <strong>Tim Pemeriksa Lapangan</strong>
            </td>
            <td style="width:50%; text-align:center; vertical-align:top; padding:0 10px 6px 10px;">
                <strong>Tanda Tangan</strong>
            </td>
        </tr>
        @forelse($anggotaTim as $pg)
        <tr>
            <td style="width:50%; text-align:center; vertical-align:bottom; padding:8px 10px 2px 10px; height:65px;">
                {{ $pg->nama }}
            </td>
            <td style="width:50%; text-align:center; vertical-align:middle; padding:4px 10px; height:65px;">
                @if($pg->tanda_tangan && $ttdSrc($pg->tanda_tangan))
                    <img src="{{ $ttdSrc($pg->tanda_tangan) }}" style="max-height:55px; max-width:170px;">
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="2" style="text-align:center; padding:10px;">
                <span style="font-style:italic; font-size:9.5pt;">(Tidak ada anggota tim tambahan)</span>
            </td>
        </tr>
        @endforelse

        {{-- BLOK 2: Penanggung Jawab & Ketua Tim --}}
        <tr>
            <td style="width:50%; text-align:center; vertical-align:top; padding:30px 10px 6px 10px;">
                <strong>Penanggung Jawab Unit Kegiatan/Usaha</strong>
            </td>
            <td style="width:50%; text-align:center; vertical-align:top; padding:30px 10px 6px 10px;">
                <strong>Tanda Tangan</strong>
            </td>
        </tr>
        <tr>
            <td style="width:50%; text-align:center; vertical-align:middle; padding:4px 10px; height:65px;">
                @if($baWasPrl->pj_usaha_tanda_tangan && $ttdSrc($baWasPrl->pj_usaha_tanda_tangan))
                    <img src="{{ $ttdSrc($baWasPrl->pj_usaha_tanda_tangan) }}" style="max-height:60px; max-width:180px;">
                @endif
            </td>
            <td style="width:50%; text-align:center; vertical-align:middle; padding:4px 10px; height:65px;">
                @if($baWasPrl->ketua_tim_tanda_tangan && $ttdSrc($baWasPrl->ketua_tim_tanda_tangan))
                    <img src="{{ $ttdSrc($baWasPrl->ketua_tim_tanda_tangan) }}" style="max-height:60px; max-width:180px;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="width:50%; text-align:center; vertical-align:top; padding:2px 10px 0 10px;">
                <span style="text-decoration:underline; font-weight:bold;">{{ $orDash($baWasPrl->penanggung_jawab_usaha) }}</span>
            </td>
            <td style="width:50%; text-align:center; vertical-align:top; padding:2px 10px 0 10px;">
                <span style="text-decoration:underline; font-weight:bold;">{{ $baWasPrl->ketua_tim_nama }}</span><br>
                <span style="font-size:9.5pt;">NIP. {{ $baWasPrl->ketua_tim_nip }}</span>
            </td>
        </tr>
    </table>

    <br>

    {{-- ============================================================
         BLOK SAKSI-SAKSI
         Layout: Numbered list — Nama, Alamat, Pekerjaan, Tanda Tangan
    ============================================================ --}}
    @if($saksiList->count() > 0)
        @foreach($saksiList as $i => $sk)
        <table style="width:100%; margin-top:10px; margin-bottom:6px; font-size:10pt; page-break-inside:avoid;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:22px; vertical-align:top; padding:2px 0; font-weight:bold;">{{ $i + 1 }}.</td>
                <td style="width:110px; vertical-align:top; padding:2px 4px;">Nama</td>
                <td style="width:14px; vertical-align:top; padding:2px 0; text-align:center;">:</td>
                <td style="vertical-align:top; padding:2px 4px;">{{ $sk->nama }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding:6px 4px 2px 4px;">Alamat</td>
                <td style="padding:6px 0 2px 0; text-align:center;">:</td>
                <td style="padding:6px 4px 2px 4px;">{{ $sk->alamat ?: '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding:6px 4px 2px 4px;">Pekerjaan</td>
                <td style="padding:6px 0 2px 0; text-align:center;">:</td>
                <td style="padding:6px 4px 2px 4px;">{{ $sk->pekerjaan ?: '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding:6px 4px 2px 4px;">Tanda Tangan</td>
                <td style="padding:6px 0 2px 0; text-align:center;">:</td>
                <td style="padding:6px 4px 2px 4px;">
                    @if($sk->tanda_tangan && $ttdSrc($sk->tanda_tangan))
                        <img src="{{ $ttdSrc($sk->tanda_tangan) }}" style="max-height:50px; max-width:160px;"><br>
                        <span style="display:inline-block; width:160px; border-bottom:1px solid #000; height:1px; margin-top:2px;"></span>
                    @else
                        <br>
                        <span style="display:inline-block; width:160px; border-bottom:1px solid #000; height:1px; margin-top:30px;"></span>
                    @endif
                </td>
            </tr>
        </table>
        @endforeach
    @endif

    <p class="tembusan">
        Tembusan:<br>
        Direktur Pengawasan PSDK
    </p>
</div>

<div class="page-break"></div>

{{-- ======================================================================
     DOKUMEN 2 — FORMULIR PENGAWASAN PEMENUHAN PELAKSANAAN DOKUMEN
     PERSETUJUAN/ KONFIRMASI KKPRL
====================================================================== --}}
<div class="doc-section">
    @include('ba-was-prl.partials.kop-surat')

    <h1 class="doc-title">FORMULIR PENGAWASAN PEMENUHAN PELAKSANAAN DOKUMEN<br>PERSETUJUAN/ KONFIRMASI KKPRL</h1>

    <table class="numbered-table">
        <tr>
            <td class="col-no">1</td><td class="col-label">Nama Pelaku Usaha</td><td class="col-sep">:</td>
            <td><strong>{{ $namaUsahaCetak }}</strong></td>
        </tr>
        <tr>
            <td class="col-no">2</td><td class="col-label">Nomor PKKPRL</td><td class="col-sep">:</td>
            <td>{{ $orDash($baWasPrl->nomor_pkkprl) }}</td>
        </tr>
        <tr>
            <td class="col-no">3</td><td class="col-label">Tanggal Terbit PKKPRL</td><td class="col-sep">:</td>
            <td>{{ Terbilang::tanggalSingkat($baWasPrl->tgl_terbit_pkkprl) }}</td>
        </tr>
        <tr>
            <td class="col-no">4</td><td class="col-label">Jenis Kegiatan</td><td class="col-sep">:</td>
            <td>{{ $jenisUsahaCetak }} {{ $baWasPrl->kbli ? '(KBLI: '.$baWasPrl->kbli.')' : '' }}</td>
        </tr>
        <tr>
            <td class="col-no">5</td><td class="col-label">Titik Koordinat Exsisting</td><td class="col-sep">:</td>
            <td>{{ $orDash($baWasPrl->titik_koordinat_existing) }}</td>
        </tr>
        <tr>
            <td class="col-no">6</td><td class="col-label">Lokasi (Desa/Pulau/Koordinat)</td><td class="col-sep">:</td>
            <td>{{ $lokasiLengkap }}</td>
        </tr>
        <tr>
            <td class="col-no">7</td><td class="col-label">Luas Area/Panjang</td><td class="col-sep">:</td>
            <td>{{ $luasAreaCetak }}</td>
        </tr>
        <tr>
            <td class="col-no">8</td><td class="col-label">Provinsi</td><td class="col-sep">:</td>
            <td>{{ $provinsiCetak }}</td>
        </tr>
        <tr>
            <td class="col-no">9</td><td class="col-label">RTRL/ RZKAW/ RZKSNT/ RZWP3K</td><td class="col-sep">:</td>
            <td>{{ $orDash($baWasPrl->nomor_perda_rzwp3k) }}</td>
        </tr>
        <tr>
            <td class="col-no">10</td><td class="col-label">Pelaksanaan Ketentuan Persetujuan/ Konfirmasi KKPRL</td><td class="col-sep">:</td>
            <td>
                {{ $labelSesuai($baWasPrl->status_kesesuaian_kkprl) }}
                @if(!empty($baWasPrl->catatan_dokumen_pkkprl))
                    <div class="catatan-block">Catatan : {{ $baWasPrl->catatan_dokumen_pkkprl }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-no">11</td>
            <td class="col-label">Pemenuhan kewajiban pelaksanaan kegiatan dalam dokumen persetujuan/konfirmasi KKPRL</td>
            <td class="col-sep">:</td>
            <td>
                {{ $labelTerpenuhi($baWasPrl->pemenuhan_kewajiban_pkkprl) }}
                @if(!empty($baWasPrl->catatan_kewajiban_pkkprl))
                    <div class="catatan-block">Catatan : {{ $baWasPrl->catatan_kewajiban_pkkprl }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-no">12</td>
            <td class="col-label">Penyampaian laporan tertulis secara berkala pelaksanaan kegiatan Pemanfaatan Ruang Laut</td>
            <td class="col-sep">:</td>
            <td>
                {{ $labelAda($baWasPrl->penyampaian_laporan_tertulis) }}
                @if(!empty($baWasPrl->catatan_laporan_tahunan))
                    <div class="catatan-block">Catatan : {{ $baWasPrl->catatan_laporan_tahunan }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-no">13</td>
            <td class="col-label">Dampak pelaksanaan dokumen KKPRL terhadap penghidupan dan akses nelayan kecil, nelayan tradisional dan pembudidaya ikan kecil</td>
            <td class="col-sep">:</td>
            <td>
                {{ $labelAda($baWasPrl->dampak_pelaksanaan_pkkprl) }}
                @if(!empty($baWasPrl->catatan_dampak_prl))
                    <div class="catatan-block">Catatan : {{ $baWasPrl->catatan_dampak_prl }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-no">14</td>
            <td class="col-label">Kesimpulan Rekomendasi dan Tindakan</td>
            <td class="col-sep">:</td>
            <td>
                @if(count($bulletItems))
                    <ul class="bullet-list">
                        @foreach($bulletItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-no">16</td><td class="col-label">Tanggal Pelaksanaan Pengawasan</td><td class="col-sep">:</td>
            <td>{{ Terbilang::tanggalSingkat($baWasPrl->tanggal_pengawasan) }}</td>
        </tr>
    </table>

    {{-- ============================================================
         BLOK TANDA TANGAN — PELAKU USAHA vs PENGAWAS KELAUTAN
    ============================================================ --}}
    <table style="width:100%; margin-top:24px; margin-bottom:10px; page-break-inside:avoid;" cellpadding="0" cellspacing="0">
        {{-- Baris label --}}
        <tr>
            <td style="width:50%; text-align:center; vertical-align:top; padding:0 10px 6px 10px;">
                <strong>Pelaku Usaha</strong>
            </td>
            <td style="width:50%; text-align:center; vertical-align:top; padding:0 10px 6px 10px;">
                <strong>Pengawas Kelautan/ Polsus PWP3K</strong>
            </td>
        </tr>
        {{-- Baris gambar tanda tangan --}}
        <tr>
            <td style="width:50%; text-align:center; vertical-align:middle; padding:4px 10px; height:65px;">
                @if($baWasPrl->pj_usaha_tanda_tangan && $ttdSrc($baWasPrl->pj_usaha_tanda_tangan))
                    <img src="{{ $ttdSrc($baWasPrl->pj_usaha_tanda_tangan) }}" style="max-height:60px; max-width:180px;">
                @endif
            </td>
            <td style="width:50%; text-align:center; vertical-align:middle; padding:4px 10px; height:65px;">
                @if($baWasPrl->ketua_tim_tanda_tangan && $ttdSrc($baWasPrl->ketua_tim_tanda_tangan))
                    <img src="{{ $ttdSrc($baWasPrl->ketua_tim_tanda_tangan) }}" style="max-height:60px; max-width:180px;">
                @endif
            </td>
        </tr>
        {{-- Baris nama (underline) + NIP --}}
        <tr>
            <td style="width:50%; text-align:center; vertical-align:top; padding:2px 10px 0 10px;">
                <span style="text-decoration:underline; font-weight:bold;">{{ $orDash($baWasPrl->penanggung_jawab_usaha) }}</span>
            </td>
            <td style="width:50%; text-align:center; vertical-align:top; padding:2px 10px 0 10px;">
                <span style="text-decoration:underline; font-weight:bold;">{{ $baWasPrl->ketua_tim_nama }}</span><br>
                <span style="font-size:9.5pt;">NIP. {{ $baWasPrl->ketua_tim_nip }}</span>
            </td>
        </tr>
    </table>

    <p class="tembusan">
        Tembusan:<br>
        1. Direktur Pengawasan SDK<br>
        2. Kepala Pangkalan PSDKP Bitung<br>
        3. Polsus PWP3K Yang bertugas
    </p>

    <p class="dicetak-footer">
        Dicetak : {{ now()->format('d/m/Y H.i') }}.{{ substr(md5($baWasPrl->id . $baWasPrl->updated_at), 0, 8) }}
    </p>
</div>
</body>
</html>

