<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BA Reklamasi - {{ $baReklamasi->nomor_ba }}</title>
    <style>
        @page { margin: 1.27cm 1.905cm 1.27cm 1.905cm; }
        * { box-sizing: border-box; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; line-height: 1.35; }
        p { margin: 4px 0; text-align: justify; }
        h1.doc-title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 4px 0 12px; line-height: 1.3; }
        table { border-collapse: collapse; width: 100%; }
        
        table.team-table { width: 100%; margin: 8px 0 10px; font-size: 9.5pt; }
        table.team-table th, table.team-table td { border: 1px solid #000; padding: 5px 6px; text-align: left; vertical-align: top; }
        table.team-table th { text-align: center; font-weight: bold; }
        table.team-table td.col-no { text-align: center; width: 26px; }

        table.kv-plain { margin: 6px 0 12px; font-size: 10pt; width: 100%; }
        table.kv-plain td { padding: 4px 6px 4px 0; vertical-align: top; }
        table.kv-plain td.kv-label { width: 220px; }
        table.kv-plain td.kv-sep { width: 12px; text-align: center; }

        .penutup { margin-top: 10px; margin-bottom: 16px; }
        .ttd-table { width: 100%; margin-top: 20px; page-break-inside: avoid; }
        .ttd-table td { text-align: center; vertical-align: top; padding: 0 10px; }
        
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
        
        .kop-surat-table { width: 100%; margin-bottom: 0; }
        .kop-logo-cell { width: 95px; vertical-align: middle; padding-right: 5px; }
        .kop-logo-cell img { width: 90px; }
        .kop-text-cell { text-align: center; vertical-align: middle; padding: 0 5px; }
        .kop-title-blue { color: #0000FF; font-weight: bold; font-size: 11.5pt; line-height: 1.18; font-family: Arial, sans-serif; text-transform: uppercase; }
        .kop-address { color: #000000; font-size: 8.5pt; line-height: 1.25; margin-top: 1px; font-family: Arial, sans-serif; }
        .kop-link { color: #0000FF; text-decoration: underline; }
        .kop-link-blue { color: #0000FF; text-decoration: underline; font-style: italic; }
        .kop-divider-thick { border-bottom: 2.5pt solid #000; margin-top: 5px; }
        .kop-divider-thin { border-bottom: 1pt solid #000; margin-top: 1.5px; margin-bottom: 14px; }
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
        } catch (\Throwable $e) { return null; }
    };
    
    $orDash = fn ($v) => ($v !== null && trim((string) $v) !== '') ? $v : '-';
    $tgl = Terbilang::tanggalLengkap($baReklamasi->tanggal_pengawasan);
    $jamFormat = $baReklamasi->jam_wita ? str_replace(':', '.', substr($baReklamasi->jam_wita, 0, 5)) : '-';
@endphp

<div class="doc-section">
    @include('ba-was-prl.partials.kop-surat')

    <h1 class="doc-title">Berita Acara Pengawasan Pelaksanaan Reklamasi</h1>

    <p>
        Pada hari ini {{ $tgl['hari'] }} tanggal {{ $tgl['tanggal'] }} bulan {{ $tgl['bulan'] }}
        tahun {{ $tgl['tahun'] }} bertempat di {{ $baReklamasi->lokasi_reklamasi ?: '-' }}, telah dilakukan pengawasan kesesuaian pelaksanaan Reklamasi oleh:
    </p>

    <table class="team-table">
        <thead>
            <tr>
                <th style="width:26px;">No</th>
                <th>Nama</th>
                <th style="width:120px;">NIP</th>
                <th style="width:120px;">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($baReklamasi->pengawas as $i => $pg)
            <tr>
                <td class="col-no">{{ $i + 1 }}</td>
                <td>{{ $pg->nama }}</td>
                <td>{{ $orDash($pg->nip) }}</td>
                <td>{{ $pg->jabatan }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;">(Data pengawas belum diisi)</td></tr>
            @endforelse
        </tbody>
    </table>

    <p>Terhadap Pelaksanaan Reklamasi yang dilaksanakan oleh:</p>
    <table class="kv-plain">
        <tr>
            <td class="kv-label">Nama Penanggung Jawab</td>
            <td class="kv-sep">:</td>
            <td><strong>{{ $orDash($baReklamasi->penanggung_jawab_usaha) }}</strong></td>
        </tr>
        <tr>
            <td class="kv-label">NIK</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baReklamasi->nik_pj) }}</td>
        </tr>
        <tr>
            <td class="kv-label">Alamat Sesuai Identitas</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baReklamasi->alamat_pj) }}</td>
        </tr>
        <tr>
            <td class="kv-label">Pelaksana Reklamasi</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baReklamasi->pelaksana_reklamasi) }}</td>
        </tr>
        <tr>
            <td class="kv-label">Lokasi Reklamasi</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baReklamasi->lokasi_reklamasi) }}</td>
        </tr>
        <tr>
            <td class="kv-label">Titik Koordinat</td>
            <td class="kv-sep">:</td>
            <td>{{ $baReklamasi->latitude }}, {{ $baReklamasi->longitude }}</td>
        </tr>
        <tr>
            <td class="kv-label">Jenis Pemanfaatan Reklamasi</td>
            <td class="kv-sep">:</td>
            <td>{{ $orDash($baReklamasi->jenis_pemanfaatan_reklamasi) }}</td>
        </tr>
    </table>

    <p>Selanjutnya dilakukan pemeriksaan dokumen perizinan pelaksanaan reklamasi sebagai berikut:</p>
    
    <table class="team-table" style="margin-top: 5px;">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 30%;">Dokumen Perizinan</th>
                <th style="width: 35%;">Obyek Pemeriksaan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-no">1.</td>
                <td>Kesesuaian Kegiatan Pemanfaatan Ruang Laut (KKPRL)</td>
                <td>
                    <table class="kv-plain" style="margin:0; font-size:9.5pt;">
                        <tr><td class="kv-label" style="width:80px; padding:1px 0;">Nomor Izin</td><td class="kv-sep" style="width:10px; padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->kkprl_nomor_izin) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Terbit Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $baReklamasi->kkprl_terbit_izin ? Terbilang::tanggalSingkat($baReklamasi->kkprl_terbit_izin) : '-' }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Pemberi Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->kkprl_pemberi_izin) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Peruntukan</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->kkprl_peruntukan) }}</td></tr>
                    </table>
                </td>
                <td>{{ $orDash($baReklamasi->kkprl_peruntukan) }}</td>
            </tr>
            <tr>
                <td class="col-no">2.</td>
                <td>Izin Pelaksanaan Reklamasi /Perizinan Berusaha</td>
                <td>
                    <table class="kv-plain" style="margin:0; font-size:9.5pt;">
                        <tr><td class="kv-label" style="width:80px; padding:1px 0;">Nomor Izin</td><td class="kv-sep" style="width:10px; padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_reklamasi_nomor) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Terbit Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $baReklamasi->izin_reklamasi_terbit ? Terbilang::tanggalSingkat($baReklamasi->izin_reklamasi_terbit) : '-' }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Pemberi Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_reklamasi_pemberi) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Peruntukan</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_reklamasi_peruntukan) }}</td></tr>
                    </table>
                </td>
                <td>{{ $orDash($baReklamasi->izin_reklamasi_peruntukan) }}</td>
            </tr>
            <tr>
                <td class="col-no">3.</td>
                <td>Izin Pelaksanaan Reklamasi /Perizinan Berusaha (Lainnya)</td>
                <td>
                    <table class="kv-plain" style="margin:0; font-size:9.5pt;">
                        <tr><td class="kv-label" style="width:80px; padding:1px 0;">Nomor Izin</td><td class="kv-sep" style="width:10px; padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_lainnya_nomor) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Terbit Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $baReklamasi->izin_lainnya_terbit ? Terbilang::tanggalSingkat($baReklamasi->izin_lainnya_terbit) : '-' }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Pemberi Izin</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_lainnya_pemberi) }}</td></tr>
                        <tr><td class="kv-label" style="padding:1px 0;">Peruntukan</td><td class="kv-sep" style="padding:1px 0;">:</td><td style="padding:1px 0;">{{ $orDash($baReklamasi->izin_lainnya_peruntukan) }}</td></tr>
                    </table>
                </td>
                <td>{{ $orDash($baReklamasi->izin_lainnya_peruntukan) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="penutup" style="margin-top: 15px;">Demikian Berita Acara Pengawasan Pelaksanaan Reklamasi ini dibuat dengan sebenar-benarnya.</p>

    <table class="ttd-table" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
        <tr>
            <td style="width:50%;">
                Pelaku Usaha
                <br><br><br>
                @if($baReklamasi->ttd_pelaku_usaha && $ttdSrc($baReklamasi->ttd_pelaku_usaha))
                    <img src="{{ $ttdSrc($baReklamasi->ttd_pelaku_usaha) }}" style="max-height:70px; max-width:140px;">
                @else
                    <div style="height:70px;"></div>
                @endif
                <br>
                <span style="text-decoration:underline; font-weight:bold;">{{ $orDash($baReklamasi->penanggung_jawab_usaha) }}</span><br>
                NIK. {{ $orDash($baReklamasi->nik_pj) }}
            </td>
            <td style="width:50%;">
                Polsus PWP3K
                <br><br><br>
                @if($baReklamasi->ttd_pengawas_1 && $ttdSrc($baReklamasi->ttd_pengawas_1))
                    <img src="{{ $ttdSrc($baReklamasi->ttd_pengawas_1) }}" style="max-height:70px; max-width:140px;">
                @else
                    <div style="height:70px;"></div>
                @endif
                <br>
                <span style="text-decoration:underline; font-weight:bold;">
                    {{ $baReklamasi->pengawas->first()->nama ?? '..........................' }}
                </span><br>
                NIP. {{ $baReklamasi->pengawas->first()->nip ?? '..........................' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 30px;">
                Paraf Pengesahan<br>
                Nama Polsus PWP3K
                <br><br><br>
                @if($baReklamasi->ttd_pengawas_2 && $ttdSrc($baReklamasi->ttd_pengawas_2))
                    <img src="{{ $ttdSrc($baReklamasi->ttd_pengawas_2) }}" style="max-height:70px; max-width:140px;">
                @else
                    <div style="height:70px;"></div>
                @endif
                <br>
                <span style="text-decoration:underline; font-weight:bold;">
                    {{ $baReklamasi->pengawas->skip(1)->first()->nama ?? '..........................' }}
                </span><br>
                NIP. {{ $baReklamasi->pengawas->skip(1)->first()->nip ?? '..........................' }}
            </td>
        </tr>
    </table>

</div>

</body>
</html>
