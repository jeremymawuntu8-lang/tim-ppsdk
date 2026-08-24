<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BA PPK - {{ $baPpk->nomor_ba }}</title>
    <style>
        @page { margin: 1.27cm 1.905cm 1.27cm 1.905cm; }
        * { box-sizing: border-box; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        body { font-family: Arial, sans-serif; font-size: 9.5pt; color: #000; line-height: 1.35; }
        p { margin: 4px 0; text-align: justify; }
        h1.doc-title { text-align: center; font-size: 11pt; font-weight: bold; margin: 4px 0 12px; line-height: 1.3; }
        h1.doc-title span { display: block; font-weight: normal; font-size: 10pt; }
        
        table { border-collapse: collapse; width: 100%; }
        table.tbl-border th, table.tbl-border td { border: 1px solid #000; padding: 4px 5px; text-align: left; vertical-align: top; }
        table.tbl-border th { text-align: center; font-weight: bold; }
        table.tbl-border td.col-no { text-align: center; width: 26px; }

        table.kv-plain { margin: 4px 0 8px; font-size: 9.5pt; width: 100%; }
        table.kv-plain td { padding: 3px 6px 3px 0; vertical-align: top; }
        table.kv-plain td.kv-label { width: 180px; }
        table.kv-plain td.kv-sep { width: 10px; text-align: center; }

        .checkbox-box { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px; position: relative; top: 2px; }
        .checkbox-box.checked { background-color: #000; } /* Simulasi centang */
        .check-item { display: inline-block; width: 32%; margin-bottom: 2px; }
        
        .section-title { font-weight: bold; margin-top: 12px; margin-bottom: 5px; }

        .ttd-table { width: 100%; margin-top: 25px; page-break-inside: avoid; }
        .ttd-table td { text-align: center; vertical-align: top; padding: 0 10px; width: 50%; }
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
    $tgl = Terbilang::tanggalLengkap($baPpk->tanggal_pengawasan);
    $jamFormat = $baPpk->jam_wita ? str_replace(':', '.', substr($baPpk->jam_wita, 0, 5)) : '...';
    
    function cb($val, $target) {
        return ($val == $target) ? '&#9745;' : '&#9744;'; // Checkbox checked vs empty
    }
    function cbBool($val) {
        return $val ? '&#9745;' : '&#9744;';
    }
    function cbArr($arr, $target) {
        if(!is_array($arr)) return '&#9744;';
        return in_array($target, $arr) ? '&#9745;' : '&#9744;';
    }
@endphp

@include('ba-was-prl.partials.kop-surat') <!-- Using ALSE's KOP for similarity -->

<h1 class="doc-title">
    BERITA ACARA <br>
    PENGAWASAN PEMANFAATAN PULAU-PULAU KECIL <br>
    <span>Nomor. {{ $baPpk->nomor_ba }}</span>
</h1>

<table class="kv-plain" style="margin-bottom: 0;">
    <tr>
        <td class="kv-label" style="width:70px;">Unit Kerja</td>
        <td class="kv-sep">:</td>
        <td>{{ $orDash($baPpk->unit_kerja) }}</td>
    </tr>
</table>

<p style="margin-bottom: 10px;">
    Pada hari ini {{ $tgl['hari'] }}, tanggal {{ $tgl['tanggal'] }}, bulan {{ $tgl['bulan'] }},
    tahun {{ $tgl['tahun'] }}, pukul {{ $jamFormat }}, di {{ $orDash($baPpk->lokasi) }}, kami yang bertanda tangan di bawah ini:
</p>

<table class="tbl-border" style="margin-bottom: 12px;">
    <thead>
        <tr>
            <th class="col-no">No.</th>
            <th>Nama</th>
            <th style="width:130px;">NIP/No. KTA</th>
            <th style="width:130px;">Jabatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($baPpk->pengawas as $i => $pg)
        <tr>
            <td class="col-no">{{ $i + 1 }}.</td>
            <td>{{ $pg->nama }}</td>
            <td>{{ $orDash($pg->nip) }}</td>
            <td>{{ $pg->jabatan }}</td>
        </tr>
        @empty
        <tr>
            <td>1.</td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td>2.</td><td></td><td></td><td></td>
        </tr>
        @endforelse
    </tbody>
</table>

<p>Telah melakukan pengawasan pemanfaatan pulau-pulau kecil dengan hasil sebagai berikut:</p>

<div class="section-title">1. Profil pelaku usaha/kegiatan</div>
<table class="tbl-border" style="font-size: 9pt;">
    <tr>
        <td style="width:20px; text-align:center;">a.</td>
        <td style="width:150px;">Identitas pelaku usaha/kegiatan</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td>1) Nama</td>
        <td colspan="2">{{ $baPpk->pelakuUsaha->nama_perusahaan ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td>2) Penanggung Jawab<br> &nbsp;&nbsp;&nbsp;&nbsp;Nama<br> &nbsp;&nbsp;&nbsp;&nbsp;No. Identitas</td>
        <td colspan="2"><br>{{ $orDash($baPpk->nama_pj) }}<br>{{ $orDash($baPpk->nik_pj) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>3) Alamat</td>
        <td colspan="2">{{ $orDash($baPpk->alamat_pj) }}</td>
    </tr>
    <tr>
        <td style="text-align:center;">b.</td>
        <td>Status Penanaman modal</td>
        <td colspan="2">
            <span style="margin-right:20px;">{!! cb($baPpk->status_modal, 'asing') !!} Modal Asing</span>
            <span>{!! cb($baPpk->status_modal, 'dalam_negeri') !!} Modal Dalam Negeri</span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">c.</td>
        <td>Kepemilikan saham<br>Nama pemilik saham</td>
        <td colspan="2">
            <div style="margin-bottom:5px;">
                <span style="margin-right:20px;">{!! cb($baPpk->kepemilikan_saham, 'swasta') !!} Swasta</span>
                <span>{!! cb($baPpk->kepemilikan_saham, 'pemerintah') !!} Pemerintah</span>
            </div>
            1. {{ $orDash($baPpk->nama_saham_1) }} <br>
            2. {{ $orDash($baPpk->nama_saham_2) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">d.</td>
        <td>Nama Pulau</td>
        <td colspan="2">{{ $orDash($baPpk->nama_pulau) }}</td>
    </tr>
    <tr>
        <td style="text-align:center;">e.</td>
        <td>Kategori Lokasi</td>
        <td colspan="2">
            <span style="margin-right:40px;">{!! cb($baPpk->kategori_lokasi, 'ppk') !!} PPK</span>
            <span>{!! cb($baPpk->kategori_lokasi, 'ppkt') !!} PPKT</span>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">f.</td>
        <td>Jenis Usaha/Kegiatan</td>
        <td colspan="2" style="padding:5px;">
            @php
            $jenisUsahaOptions = [
                'Konservasi', 'Diklat', 'Litbang', 'Budidaya', 'Pertanian', 'Peternakan',
                'Perkebunan', 'Pergudangan', 'Pariwisata', 'Industri', 'Agroforestry',
                'Pertambangan Tanah Jarang', 'Energi baru dan terbarukan', 'Usaha minyak dan gas bumi',
                'Kepelabuhan/perhubungan', 'Pemukiman', 'Usaha perikanan/kelautan', 'Pertambangan Minerba',
                'Fasum/fasos', 'Adat istiadat/upacara', 'Hankam', 'KSN yang ditetapkan Presiden'
            ];
            @endphp
            @foreach($jenisUsahaOptions as $idx => $jenis)
                <div class="check-item" style="{{ $idx % 2 == 0 ? 'width: 45%;' : 'width: 53%;' }}">
                    {!! cbArr($baPpk->jenis_usaha, $jenis) !!} {{ $jenis }}
                </div>
            @endforeach
        </td>
    </tr>
</table>

<div class="section-title">2. Pemeriksaan Perizinan</div>
<table class="tbl-border" style="margin-bottom: 5px;">
    <tr><td colspan="3" style="padding:5px; font-weight:bold;">a. Syarat wajib memiliki Rekomendasi PPK</td></tr>
    <tr><td style="width:20px; text-align:center;">1)</td><td>Belum tersedia RDTR</td><td style="width:100px;">{!! cbBool($baPpk->syarat_rdtr_belum) !!} Ya &nbsp; {!! cbBool(!$baPpk->syarat_rdtr_belum) !!} Tidak</td></tr>
    <tr><td style="text-align:center;">2)</td><td>Telah tersedia RDTR namun belum terintegrasi OSS</td><td>{!! cbBool($baPpk->syarat_rdtr_non_oss) !!} Ya &nbsp; {!! cbBool(!$baPpk->syarat_rdtr_non_oss) !!} Tidak</td></tr>
    <tr><td style="text-align:center;">3)</td><td>RTR belum memuat zonasi pemanfaatan PPK < 100 km2</td><td>{!! cbBool($baPpk->syarat_rtr_zonasi) !!} Ya &nbsp; {!! cbBool(!$baPpk->syarat_rtr_zonasi) !!} Tidak</td></tr>
    <tr><td style="text-align:center;">4)</td><td>Tidak termasuk kondisi tertentu yang dikecualikan dalam penerbitan PKKPR</td><td>{!! cbBool($baPpk->syarat_pengecualian_pkkpr) !!} Ya &nbsp; {!! cbBool(!$baPpk->syarat_pengecualian_pkkpr) !!} Tidak</td></tr>
</table>
<div style="font-size: 8pt; margin-bottom: 10px;">*) Pemeriksaan syarat wajib melalui <a href="https://tataruang.atrbpn.go.id/protaru/rdtr/status/RDTR#">https://tataruang.atrbpn.go.id/protaru/rdtr/status/RDTR#</a></div>

<table class="tbl-border" style="font-size: 9pt;">
    <thead>
        <tr>
            <th colspan="6" style="padding: 5px; text-align:left;"><strong>Rekomendasi, Perizinan Dasar dan Perizinan Berusaha</strong></th>
        </tr>
    </thead>
    <tbody style="page-break-inside: auto;">
        <tr>
            <td style="width:20px; text-align:center;">1)</td>
            <td style="width:150px;">REKOMENDASI PPK</td>
            <td style="width:10px;"></td>
            <td colspan="2">{!! cbBool($baPpk->rek_ppk_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->rek_ppk_ada) !!} Tidak Ada</td>
            <td style="width:50px;"></td>
        </tr>
        <tr>
            <td></td>
            <td>- Jenis</td><td></td>
            <td colspan="2">
                <div>{!! cb($baPpk->rek_ppk_jenis, 'Rekomendasi PPK < 100 km2') !!} Rekomendasi PPK &lt; 100 km2</div>
                <div>{!! cb($baPpk->rek_ppk_jenis, 'Rekomendasi PPK oleh PMA') !!} Rekomendasi PPK oleh PMA</div>
            </td>
            <td>{!! cb($baPpk->rek_ppk_jenis_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_jenis_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Nomor</td><td>:</td><td colspan="2">{{ $orDash($baPpk->rek_ppk_nomor) }}</td>
            <td>{!! cb($baPpk->rek_ppk_nomor_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_nomor_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Tanggal Terbit</td><td>:</td><td colspan="2">{{ $baPpk->rek_ppk_tgl ? $baPpk->rek_ppk_tgl->format('d-m-Y') : '-' }}</td>
            <td>{!! cb($baPpk->rek_ppk_tgl_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_tgl_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Penerbit</td><td>:</td><td colspan="2">{{ $orDash($baPpk->rek_ppk_penerbit) }}</td>
            <td>{!! cb($baPpk->rek_ppk_penerbit_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_penerbit_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Masa Berlaku</td><td>:</td>
            <td colspan="2">{{ $orDash($baPpk->rek_ppk_masa_berlaku) }}<br><span style="font-size:8pt;">Ket: Rekomendasi berlaku 2 tahun jika PKKPR belum diterbitkan</span></td>
            <td>{!! cb($baPpk->rek_ppk_masa_berlaku_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_masa_berlaku_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Jenis Kegiatan</td><td>:</td><td colspan="2">{{ $orDash($baPpk->rek_ppk_jenis_kegiatan) }}</td>
            <td>{!! cb($baPpk->rek_ppk_jenis_kegiatan_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_jenis_kegiatan_sts, 'TS') !!} TS</td>
        </tr>
        <tr>
            <td></td>
            <td>- Luas pada izin<br>- Luas pemanfaatan</td><td>:<br> </td>
            <td colspan="2">{{ $orDash($baPpk->rek_ppk_luas_izin) }} (Ha)<br>{{ $orDash($baPpk->rek_ppk_luas_pemanfaatan) }} (Ha)</td>
            <td>{!! cb($baPpk->rek_ppk_luas_izin_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_luas_izin_sts, 'TS') !!} TS<br> </td>
        </tr>
        <tr>
            <td></td>
            <td>- Koordinat pada dokumen perizinan<br><br>- Koordinat eksisting pemanfaatan</td><td>:<br><br>:</td>
            <td colspan="2">{{ $orDash($baPpk->rek_ppk_koordinat_izin) }}<br><br>{{ $orDash($baPpk->rek_ppk_koordinat_eksisting) }}</td>
            <td>{!! cb($baPpk->rek_ppk_koordinat_izin_sts, 'S') !!} S &nbsp; {!! cb($baPpk->rek_ppk_koordinat_izin_sts, 'TS') !!} TS<br><br></td>
        </tr>

        <!-- PKKPR -->
        <tr>
            <td style="text-align:center;">2)</td>
            <td>PKKPR</td><td>:</td><td colspan="2">{!! cbBool($baPpk->pkkpr_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->pkkpr_ada) !!} Tidak Ada</td>
            <td></td>
        </tr>
        <tr><td></td><td>- Nomor</td><td>:</td><td colspan="2">{{ $orDash($baPpk->pkkpr_nomor) }}</td><td></td></tr>
        <tr><td></td><td>- Tanggal Terbit</td><td>:</td><td colspan="2">{{ $baPpk->pkkpr_tgl ? $baPpk->pkkpr_tgl->format('d-m-Y') : '-' }}</td><td></td></tr>
        <tr><td></td><td>- Penerbit</td><td>:</td><td colspan="2">{{ $orDash($baPpk->pkkpr_penerbit) }}</td><td></td></tr>
        <tr><td></td><td>- Luas<br>- Koordinat</td><td>:<br>:</td><td colspan="2">{{ $orDash($baPpk->pkkpr_luas) }} (Ha)<br>{{ $orDash($baPpk->pkkpr_koordinat) }}</td><td></td></tr>

        <!-- LINGKUNGAN -->
        <tr>
            <td style="text-align:center;">3)</td>
            <td>PERSETUJUAN LINGKUNGAN</td><td></td><td colspan="2">{!! cbBool($baPpk->lingkungan_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->lingkungan_ada) !!} Tidak Ada</td>
            <td></td>
        </tr>
        <tr><td></td><td>Nomor<br>Tanggal Terbit</td><td>:<br>:</td><td colspan="2">{{ $orDash($baPpk->lingkungan_nomor) }}<br>{{ $baPpk->lingkungan_tgl ? $baPpk->lingkungan_tgl->format('d-m-Y') : '-' }}</td><td></td></tr>
        <tr><td></td><td>Penerbit</td><td>:</td><td colspan="2">{{ $orDash($baPpk->lingkungan_penerbit) }}</td><td></td></tr>

        <!-- NIB -->
        <tr>
            <td style="text-align:center;">4)</td>
            <td>NIB</td><td>:</td><td colspan="2">{!! cbBool($baPpk->nib_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->nib_ada) !!} Tidak Ada</td>
            <td></td>
        </tr>
        <tr><td></td><td>Nomor</td><td>:</td><td colspan="2">{{ $orDash($baPpk->nib_nomor) }}</td><td></td></tr>
        <tr><td></td><td>Tanggal Terbit</td><td>:</td><td colspan="2">{{ $baPpk->nib_tgl ? $baPpk->nib_tgl->format('d-m-Y') : '-' }}</td><td></td></tr>
        <tr><td></td><td>Kode KBLI</td><td>:</td><td colspan="2">{{ $orDash($baPpk->nib_kbli) }}</td><td></td></tr>

        <!-- PERIZINAN BERUSAHA -->
        <tr>
            <td style="text-align:center;">5)</td>
            <td>PERIZINAN BERUSAHA</td><td></td><td colspan="2">{!! cbBool($baPpk->izin_usaha_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->izin_usaha_ada) !!} Tidak Ada</td>
            <td></td>
        </tr>
        <tr><td></td><td>Nomor</td><td>:</td><td colspan="2">{{ $orDash($baPpk->izin_usaha_nomor) }}</td><td></td></tr>
        <tr><td></td><td>Tanggal Terbit</td><td>:</td><td colspan="2">{{ $baPpk->izin_usaha_tgl ? $baPpk->izin_usaha_tgl->format('d-m-Y') : '-' }}</td><td></td></tr>
        <tr><td></td><td>Penerbit</td><td>:</td><td colspan="2">{{ $orDash($baPpk->izin_usaha_penerbit) }}</td><td></td></tr>
        <tr><td></td><td>Masa Berlaku</td><td>:</td><td colspan="2">{{ $orDash($baPpk->izin_usaha_masa) }}</td><td></td></tr>
        <tr><td></td><td>Jenis kegiatan usaha<br>Luas kegiatan usaha<br>Lokasi kegiatan usaha</td><td>:<br>:<br>:</td><td colspan="2">{{ $orDash($baPpk->izin_usaha_jenis) }}<br>{{ $orDash($baPpk->izin_usaha_luas) }} (Ha)<br>{{ $orDash($baPpk->izin_usaha_lokasi) }}</td><td></td></tr>
        <tr><td></td><td>Koordinat</td><td>:</td><td colspan="2">{{ $orDash($baPpk->izin_usaha_koordinat) }}</td><td></td></tr>

        <!-- DOKUMEN LAINNYA -->
        <tr>
            <td style="text-align:center;">f.</td>
            <td>DOKUMEN LAINNYA</td><td>:</td><td colspan="2">{!! cbBool($baPpk->dok_lain_ada) !!} Ada &nbsp; {!! cbBool(!$baPpk->dok_lain_ada) !!} Tidak Ada</td>
            <td></td>
        </tr>
        <tr><td></td><td>Jenis Dokumen</td><td>:</td><td colspan="2">{{ $orDash($baPpk->dok_lain_jenis) }}</td><td></td></tr>
        <tr><td></td><td>Nomor</td><td>:</td><td colspan="2">{{ $orDash($baPpk->dok_lain_nomor) }}</td><td></td></tr>
        <tr><td></td><td>Tanggal Terbit</td><td>:</td><td colspan="2">{{ $baPpk->dok_lain_tgl ? $baPpk->dok_lain_tgl->format('d-m-Y') : '-' }}</td><td></td></tr>
        <tr><td></td><td>Penerbit</td><td>:</td><td colspan="2">{{ $orDash($baPpk->dok_lain_penerbit) }}</td><td></td></tr>
        <tr><td></td><td>Lokasi</td><td>:</td><td colspan="2">{{ $orDash($baPpk->dok_lain_lokasi) }}</td><td></td></tr>
    </tbody>
</table>

<div class="section-title">3. Pemeriksaan pemenuhan ketentuan pemanfaatan pulau pulau kecil</div>
<table class="tbl-border">
    <tr>
        <td style="width:20px; text-align:center;">a.</td><td>30% luasan lahan yang dikelola untuk ruang terbuka hijau</td>
        <td style="width:70px;">{!! cb($baPpk->pemenuhan_rth, 'S') !!} S &nbsp; {!! cb($baPpk->pemenuhan_rth, 'TS') !!} TS</td>
    </tr>
    <tr>
        <td style="text-align:center;">b.</td><td>Kegiatan pemanfaatan PPK sesuai dengan RTR</td>
        <td>{!! cb($baPpk->pemenuhan_rtr, 'S') !!} S &nbsp; {!! cb($baPpk->pemenuhan_rtr, 'TS') !!} TS</td>
    </tr>
    <tr>
        <td style="text-align:center;">c.</td><td>Pemberian akses publik</td>
        <td>{!! cb($baPpk->pemenuhan_akses, 'S') !!} S &nbsp; {!! cb($baPpk->pemenuhan_akses, 'TS') !!} TS</td>
    </tr>
    <tr>
        <td style="text-align:center;">d.</td><td>Jenis kegiatan sesuai dengan luas, topografi dan tipologi pulau<br><span style="font-size:8pt;">(diisi merujuk kepada Lampiran III)</span></td>
        <td>{!! cb($baPpk->pemenuhan_jenis, 'S') !!} S &nbsp; {!! cb($baPpk->pemenuhan_jenis, 'TS') !!} TS</td>
    </tr>
</table>

<table class="kv-plain" style="margin-top:10px;">
    <tr>
        <td style="width:20px; text-align:center;">4.</td>
        <td style="width:250px;">Dugaan pelanggaran<br>Jika ada, jelaskan</td>
        <td colspan="2">
            <span style="margin-right:20px;">{!! cbBool($baPpk->dugaan_pelanggaran_ada) !!} Ada</span>
            <span>{!! cbBool(!$baPpk->dugaan_pelanggaran_ada) !!} Tidak Ada</span>
            <br>
            {{ $orDash($baPpk->dugaan_pelanggaran_ket) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">5.</td>
        <td>Dugaan kerusakan/pencemaran/kerugian masyarakat<br>Jika ada, jelaskan</td>
        <td colspan="2">
            <span style="margin-right:20px;">{!! cbBool($baPpk->dugaan_kerusakan_ada) !!} Ada</span>
            <span>{!! cbBool(!$baPpk->dugaan_kerusakan_ada) !!} Tidak Ada</span>
            <br>
            {{ $orDash($baPpk->dugaan_kerusakan_ket) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">6.</td>
        <td>Kesimpulan</td>
        <td style="width:10px;">:</td>
        <td>{{ $orDash($baPpk->kesimpulan) }}</td>
    </tr>
    <tr>
        <td style="text-align:center;">7.</td>
        <td>Rekomendasi</td>
        <td>:</td>
        <td>
            @php $reks = ['Pelaku usaha dinyatakan taat', 'Pelaku usaha mengurus perizinan', 'Pelaku usaha memperbaiki kerusakan/pencemaran', 'Dilakukan pemeriksaan lanjutan', 'Penerapan sanksi']; @endphp
            @foreach($reks as $idx => $rek)
                <div>{{ $idx+1 }}. {!! cbArr($baPpk->rekomendasi_tindakan, $rek) !!} {{ $rek }}</div>
            @endforeach
            <div>6. {!! $baPpk->rekomendasi_lainnya ? '&#9745;' : '&#9744;' !!} Lainnya: {{ $baPpk->rekomendasi_lainnya }}</div>
            <div style="font-size:8pt; font-style:italic; margin-top:2px;">Ket: Tentukan rekomendasi yang sesuai</div>
        </td>
    </tr>
</table>

<table class="ttd-table" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            Pelaku Usaha
            <br><br><br>
            @if($baPpk->ttd_pelaku_usaha && $ttdSrc($baPpk->ttd_pelaku_usaha))
                <img src="{{ $ttdSrc($baPpk->ttd_pelaku_usaha) }}" style="max-height:70px; max-width:140px;">
            @else
                <div style="height:70px;"></div>
            @endif
            <br>
            <span style="text-decoration:underline;">({{ $orDash($baPpk->nama_pj) }})</span><br>
            NIK. {{ $orDash($baPpk->nik_pj) }}
        </td>
        <td>
            Polsus PWP-3-K
            <br><br><br>
            @if($baPpk->ttd_pengawas_1 && $ttdSrc($baPpk->ttd_pengawas_1))
                <img src="{{ $ttdSrc($baPpk->ttd_pengawas_1) }}" style="max-height:70px; max-width:140px;">
            @else
                <div style="height:70px;"></div>
            @endif
            <br>
            <span style="text-decoration:underline;">
                ({{ $baPpk->pengawas->first()->nama ?? '..........................' }})
            </span><br>
            NIP/KTA. {{ $baPpk->pengawas->first()->nip ?? '..........................' }}
        </td>
    </tr>
</table>

</body>
</html>
