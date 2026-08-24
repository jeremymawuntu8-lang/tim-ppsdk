<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BA Pencemaran - {{ $baPencemaran->nomor_ba }}</title>
    <style>
        @page { margin: 1.27cm 1.905cm 1.27cm 1.905cm; }
        * { box-sizing: border-box; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        body { font-family: "Calibri", "Arial", sans-serif; font-size: 11pt; color: #000; line-height: 1.2; }
        p { margin: 4px 0; text-align: justify; }
        
        table { border-collapse: collapse; width: 100%; }
        table.tbl-border th, table.tbl-border td { border: 1px solid #000; padding: 4px 5px; text-align: left; vertical-align: top; }
        table.tbl-border th { text-align: center; font-weight: bold; }
        
        .section-title { font-weight: bold; margin-top: 12px; margin-bottom: 5px; }

        .ttd-table { width: 100%; margin-top: 25px; page-break-inside: avoid; }
        .ttd-table td { text-align: center; vertical-align: top; padding: 0 10px; width: 50%; }
        .page-break { page-break-before: always; }
        
        .kop-table { width: 100%; border-bottom: 3px solid #000; margin-bottom: 2px; padding-bottom: 5px;}
        .kop-table td { vertical-align: middle; }
        .kop-text { text-align: center; line-height: 1.1; }
        
        .sub-list { margin-left: 20px; }
        td { vertical-align: top; }
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
    $tgl = Terbilang::tanggalLengkap($baPencemaran->tanggal_pengawasan);
    $jamFormat = $baPencemaran->jam_wita ? str_replace(':', '.', substr($baPencemaran->jam_wita, 0, 5)) : '...';
    
    function cb($val, $target) {
        return ($val == $target) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#9745;</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">&#9744;</span>';
    }
    function cbBool($val) {
        return ($val === "1" || $val === 1 || $val === true) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#9745;</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">&#9744;</span>';
    }
    function cbBoolFalse($val) {
        return ($val === "0" || $val === 0 || $val === false) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#9745;</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">&#9744;</span>';
    }
    function cbArr($arr, $target) {
        if(!is_array($arr)) return '<span style="font-family: DejaVu Sans, sans-serif;">&#9744;</span>';
        return in_array($target, array_keys(array_filter($arr))) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#9745;</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">&#9744;</span>';
    }
    
    $logoBase64 = base64_encode(file_get_contents(public_path('images/kop-kkp.png')));
    $e1 = is_array($baPencemaran->lampiran_e1) ? $baPencemaran->lampiran_e1 : [];
    $e2 = is_array($baPencemaran->lampiran_e2) ? $baPencemaran->lampiran_e2 : [];
    $e3 = is_array($baPencemaran->lampiran_e3) ? $baPencemaran->lampiran_e3 : [];
    $e4 = is_array($baPencemaran->lampiran_e4) ? $baPencemaran->lampiran_e4 : [];
    $e5 = is_array($baPencemaran->lampiran_e5) ? $baPencemaran->lampiran_e5 : [];
    $e6 = is_array($baPencemaran->lampiran_e6) ? $baPencemaran->lampiran_e6 : [];
    $hp = is_array($baPencemaran->hasil_pengawasan) ? $baPencemaran->hasil_pengawasan : [];
@endphp

@include('ba-was-prl.partials.kop-surat')


<div style="text-align:center; font-weight:bold; font-size:12pt; margin-bottom:15px;">
    BERITA ACARA HASIL PENGAWASAN<br>
    PENCEMARAN SUMBER DAYA IKAN DAN LINGKUNGANNYA
</div>

<p>
    Pada hari ini {{ $tgl['hari'] }} tanggal {{ $tgl['tanggal'] }} bulan {{ $tgl['bulan'] }} tahun {{ $tgl['tahun'] }} pukul {{ $jamFormat }} WITA bertempat di {{ $orDash($baPencemaran->lokasi_pengawasan) }}, kami yang bertanda tangan di bawah ini:
</p>

<table style="width:100%; border:1px solid #000; margin-bottom: 15px;" class="tbl-border">
    <tr>
        <th style="width:30px;">No</th>
        <th>Nama</th>
        <th>NIP</th>
        <th>Jabatan</th>
    </tr>
    @forelse($baPencemaran->pengawas as $i => $pg)
    <tr>
        <td style="text-align:center;">{{ $i + 1 }}</td>
        <td>{{ $pg->nama }}</td>
        <td>{{ $orDash($pg->nip) }}</td>
        <td>{{ $pg->jabatan }}</td>
    </tr>
    @empty
    <tr><td style="text-align:center;">1</td><td></td><td></td><td></td></tr>
    <tr><td style="text-align:center;">2</td><td></td><td></td><td></td></tr>
    <tr><td style="text-align:center;">3</td><td></td><td></td><td></td></tr>
    @endforelse
</table>

<p>Telah melakukan pengawasan:</p>
<table style="width:100%; margin-bottom:15px;">
    <tr><td style="width:250px;">{!! cb($baPencemaran->jenis_pengawasan, 'rutin') !!} Pengawasan rutin</td></tr>
    <tr><td>{!! cb($baPencemaran->jenis_pengawasan, 'insidental') !!} Pengawasan insidental</td></tr>
    <tr><td colspan="2">Berdasarkan laporan pengaduan nomor:<br>{{ $orDash($baPencemaran->laporan_pengaduan_nomor) }}<br>Tanggal: {{ $baPencemaran->laporan_pengaduan_tgl?->format('d-m-Y') ?? '-' }}</td></tr>
</table>

<div class="section-title">Lokasi Usaha/Kegiatan</div>
<table style="width:100%; margin-bottom:15px;">
    <tr>
        <td style="padding-left:15px;">1. Alamat/Lokasi Kegiatan</td>
        <td>:</td>
        <td style="font-weight:bold;">{{ $baPencemaran->alamat_kantor ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding-left:15px;">2. Titik Koordinat</td>
        <td>:</td>
        <td style="font-weight:bold;">{{ $baPencemaran->koordinat_kantor ?? '-' }}</td>
    </tr>
</table>

<div class="section-title">Informasi Pelaku Usaha/Pelaku Kegiatan</div>
<table style="width:100%; margin-bottom:15px;">
    <tr><td style="width:220px;">Nama Usaha/Nama Kegiatan</td><td style="width:10px;">:</td><td>{{ $orDash($baPencemaran->nama_usaha_kegiatan) }}</td></tr>
    <tr><td>Nomor Induk Berusaha</td><td>:</td><td>{{ $orDash($baPencemaran->nib) }}</td></tr>
    <tr><td>Luas pemanfaatan ruang darat</td><td>:</td><td>{{ $orDash($baPencemaran->luas_darat) }} (Ha)</td></tr>
    <tr><td>Luas pemanfaatan ruang laut</td><td>:</td><td>{{ $orDash($baPencemaran->luas_laut) }} (Ha)</td></tr>
    <tr><td style="padding-left:15px;">Zona</td><td>:</td><td>{{ $orDash($baPencemaran->zona_sub_zona) }}</td></tr>
    <tr><td style="padding-left:15px;">Sub Zona</td><td>:</td><td>-</td></tr>
    <tr><td>Nama Penanggung Jawab</td><td>:</td><td>{{ $orDash($baPencemaran->nama_pj) }}</td></tr>
    <tr><td>Nomor Identitas</td><td>:</td><td>{{ $orDash($baPencemaran->nik_pj) }}</td></tr>
    <tr><td>Jabatan</td><td>:</td><td>{{ $orDash($baPencemaran->jabatan_pj) }}</td></tr>
    <tr><td>Alamat Kantor</td><td>:</td><td>{{ $orDash($baPencemaran->alamat_kantor) }}</td></tr>
    <tr><td>Alamat Email</td><td>:</td><td>{{ $orDash($baPencemaran->email_pj) }}</td></tr>
    <tr><td>No. Telp./HP</td><td>:</td><td>{{ $orDash($baPencemaran->no_telp_pj) }}</td></tr>
</table>

<div class="section-title" style="text-align:center; margin-top:20px;">Jenis Usaha/Kegiatan<br>Sektor Kelautan</div>
<table style="width:100%; margin-bottom:15px; font-size:10pt;">
    <tr>
        <td style="width:50%;">
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_alam') !!} pengusahaan pariwisata alam perairan di Kawasan Konservasi<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_akomodasi') !!} akomodasi wisata<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_makanan') !!} makanan dan minuman<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_mangrove') !!} wisata mangrove<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_marina') !!} marina<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_tirta') !!} usaha wisata tirta<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_transportasi') !!} transportasi wisata
            </div>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_kapal_tenggelam') !!} pengangkatan benda muatan kapal tenggelam<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_garam') !!} produksi garam<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_garam_pra') !!} pra produksi<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_garam_produksi') !!} produksi<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_garam_pasca') !!} pasca produksi<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_garam_pengolahan') !!} pengolahan
            </div>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_biofarmakologi') !!} biofarmakologi<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_bioteknologi') !!} bioteknologi<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_air_laut') !!} pemanfaatan air laut selain energi<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_reklamasi') !!} pelaksanaan reklamasi<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_reklamasi_pelaksanaan') !!} pelaksanaan reklamasi<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_reklamasi_material') !!} pengambilan sumber material reklamasi
            </div>
        </td>
        <td style="width:50%;">
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma') !!} pemanfaatan pulau-pulau kecil dan perairan di sekitarnya dalam rangka penanaman modal asing<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk') !!} pemanfaatan pulau-pulau kecil di bawah 100 km2 (seratus kilometer persegi)<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_budidaya') !!} budidaya laut<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_wisata') !!} usaha wisata tirta<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_industri') !!} usaha perikanan dan kelautan serta industri perikanan secara lestari<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_organik') !!} pertanian organik<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_peternakan') !!} peternakan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_storage') !!} fasilitas penyimpanan minyak (oil storage)<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma_pemukiman') !!} permukiman di atas air
            </div>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_pasir') !!} pemanfaatan pasir laut<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_bangunan') !!} bangunan laut dalam kegiatan wisata tirta lainnya<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kel_pipa') !!} pipa dan/atau kabel bawah Laut.
        </td>
    </tr>
</table>
<div style="font-size:9pt; font-style:italic; text-align:center; margin-bottom:15px;">isi dengan tanda (√) sesuai jenis kegiatan yang diperiksa pada kotak yang tersedia</div>

<div class="section-title" style="text-align:center;">Sektor Perikanan</div>
<table style="width:100%; margin-bottom:15px; font-size:10pt;">
    <tr>
        <td style="width:50%;">
            {!! cbArr($baPencemaran->jenis_usaha, 'kan_kapal') !!} kapal perikanan<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kan_budidaya') !!} pembudidayaan ikan<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_b_kja') !!} keramba jaring apung (KJA)<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_b_tambak') !!} kolam/tambak pembudidayaan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_b_lain') !!} tempat pembudidayaan ikan lainnya
            </div>
            {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah') !!} unit pengolahan ikan<br>
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_1') !!} penggaraman ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_2') !!} pengeringan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_3') !!} pengasapan/ pemanggangan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_4') !!} pembekuan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_5') !!} pemindangan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_6') !!} peragian/fermentasi ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_7') !!} pengolahan berbasis daging lumatan dan surimi
            </div>
        </td>
        <td style="width:50%;">
            <div class="sub-list">
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_8') !!} pendinginan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_9') !!} pengalengan ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_10') !!} pengolahan rumput laut<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_11') !!} pembuatan minyak ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_12') !!} pencucian ikan dan pembuatan tepung ikan<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_13') !!} pengolahan kerupuk, keripik, peyek, dan sejenisnya<br>
                {!! cbArr($baPencemaran->jenis_usaha, 'kan_olah_14') !!} pengolahan dan pengawetan lainnya
            </div>
            <br>
            {!! cbArr($baPencemaran->jenis_usaha, 'kan_pelabuhan') !!} pelabuhan perikanan
        </td>
    </tr>
</table>

<div class="section-title" style="text-align:center;">Kegiatan dan/atau Usaha Lain</div>
<table style="width:100%; margin-bottom:15px; font-size:10pt;">
    <tr>
        <td style="width:50%;">
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_pariwisata') !!} pariwisata<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_pelabuhan') !!} pelabuhan umum<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_tambang') !!} pertambangan minyak, gas, mineral dan batubara<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_transport') !!} transportasi laut<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_industri') !!} industri
        </td>
        <td style="width:50%;">
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_listrik') !!} ketenagalistrikan<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_sampah') !!} kebocoran sampah padat dan limbah cair dari kegiatan rumah tangga/ permukiman dari darat ke perairan laut<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_tani') !!} pertanian, perkebunan, dan/atau peternakan<br>
            {!! cbArr($baPencemaran->jenis_usaha, 'lain_dampak') !!} kegiatan dan/atau usaha lain yang berpotensi mencemari Sumber Daya Ikan dan lingkungannya
        </td>
    </tr>
</table>

<div class="page-break"></div>

<div class="section-title">Pemeriksaan Perizinan Sesuai Kegiatan</div>
<table class="tbl-border" style="width:100%; margin-bottom:15px; font-size:10pt;">
    <tr>
        <th>Persyaratan Dasar</th>
        <th>Nomor</th>
        <th>Tanggal Terbit dan Masa Berlaku</th>
        <th>Instansi Penerbit</th>
    </tr>
    @php $pDasar = is_array($baPencemaran->perizinan_dasar) ? $baPencemaran->perizinan_dasar : []; @endphp
    <tr>
        <td>PKKPRL/KKRL<br>{!! cb(($pDasar['pkkprl']['status'] ?? ''), 'ada') !!} ada<br>{!! cb(($pDasar['pkkprl']['status'] ?? ''), 'tidak') !!} tidak ada</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['pkkprl']['nomor'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['pkkprl']['tgl'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['pkkprl']['instansi'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>Persetujuan Lingkungan<br>{!! cb(($pDasar['lingkungan']['status'] ?? ''), 'ada') !!} ada<br>{!! cb(($pDasar['lingkungan']['status'] ?? ''), 'tidak') !!} tidak ada</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['lingkungan']['nomor'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['lingkungan']['tgl'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['lingkungan']['instansi'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>Izin Mendirikan Bangunan<br>{!! cb(($pDasar['imb']['status'] ?? ''), 'ada') !!} ada<br>{!! cb(($pDasar['imb']['status'] ?? ''), 'tidak') !!} tidak ada</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['imb']['nomor'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['imb']['tgl'] ?? '-' }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $pDasar['imb']['instansi'] ?? '-' }}</td>
    </tr>
</table>

<table class="tbl-border" style="width:100%; margin-bottom:15px; font-size:10pt;">
    <tr>
        <td style="width:50%;">Dokumen Rencana Pencegahan Pencemaran</td>
        <td>
            {!! cbArr($baPencemaran->dokumen_pencegahan, 'amdal') !!} AMDAL<br>
            {!! cbArr($baPencemaran->dokumen_pencegahan, 'uklupl') !!} UKL-UPL<br>
            {!! cbArr($baPencemaran->dokumen_pencegahan, 'sppl') !!} SPPL<br>
            Lainnya: {{ (is_array($baPencemaran->dokumen_pencegahan) ? ($baPencemaran->dokumen_pencegahan['lain_text'] ?? '') : '') }}
        </td>
    </tr>
    <tr>
        <td>Perizinan Berusaha</td>
        <td>
            Sebutkan:<br>
            {{ is_array($baPencemaran->perizinan_berusaha) ? ($baPencemaran->perizinan_berusaha['sebutkan'] ?? '-') : '-' }}
        </td>
    </tr>
</table>

<div class="page-break"></div>

<div class="section-title">Hasil Pengawasan</div>
<table class="tbl-border" style="font-size:10pt; width:100%; margin-bottom:15px;">
    <tr><th style="width:70%;">Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Sektor Kelautan)</th><th style="width:15%;">Sesuai</th><th style="width:15%;">Tidak Sesuai</th></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_wisata_alam') !!} Pengusahaan pariwisata alam perairan di Kawasan Konservasi</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_kapal_tenggelam') !!} Pengangkatan benda muatan kapal tenggelam</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_biofarmakologi') !!} Biofarmakologi</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_bioteknologi') !!} Bioteknologi</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_air_laut') !!} Pemanfataan Air Laut Selain Energi</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">standar pengelolaan bahan-bahan yang berpotensi menjadi penyebab pencemaran perairan<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.4 Pengelolaan Bahan Pencemar)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['e_4'] ?? '', 'tidak') !!}</td></tr>
    
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_garam') !!} Produksi garam</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian tempat pengelolaan seluruh bahan yang digunakan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan layout ruangan/tempat produksi pada dokumen AMDAL/UKL-UPL/SPPL dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">proses daur ulang seluruh bahan yang digunakan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan proses daur ulang pada dokumen AMDAL/UKL-UPL/SPPL dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['f_3'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_reklamasi') !!} Reklamasi</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">dokumen PKKPRL atau KKRL<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.5 Kesesuaian Dokumen PKKPRL atau KKRL)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pelaksanaan reklamasi dan/atau pengambilan sumber material reklamasi dengan Izin Reklamasi<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan koordinat pelaksanaan reklamasi dan pengambilan material dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian tahapan pelaksanaan reklamasi sesuai dengan standar yang ditetapkan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan metode pelaksanaan reklamasi dan persyaratan yang ditetapkan dalam Izin Reklamasi dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_4'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">material reklamasi tidak mengandung kategori bahan beracun dan berbahaya<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan melakukan uji sampel material reklamasi di laboratorium terakreditasi)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_5'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['g_5'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma') !!} Pemanfaatan pulau-pulau kecil dan perairan di sekitarnya dalam rangka penanaman modal asing dan pemanfaatan pulau-pulau kecil di bawah 100 km2 (untuk kegiatan budidaya laut, usaha wisata tirta, usaha perikanan dan kelautan, industri perikanan secara lestari)</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">standar pengelolaan bahan-bahan yang berpotensi menjadi penyebab pencemaran perairan<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.4 Pengelolaan Bahan Pencemar)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['h_4'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_ppk_pma') !!} Pemanfaatan pulau-pulau kecil dan perairan di sekitarnya dalam penanaman modal asing dan pemanfaatan pulau-pulau kecil di bawah 100 km2 (untuk kegiatan pertanian organic, peternakan, fasilitas penyimpanan minyak, permukiman di atas air)</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_bangunan') !!} bangunan laut dalam kegiatan wisata tirta lainnya</td></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_pipa') !!} pipa dan/atau kabel bawah laut</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_a'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_a'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pelaksanaan pencegahan pencemaran dengan dokumen AMDAL/UKL-UPL/SPPL<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan persyaratan pelaksanaan kegiatan dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_b'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_b'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan sarana sanitasi<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan visual ketersediaan sarana sanitasi)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_c'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_c'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan papan informasi terkait pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan memantau ketersediaan papan informasi pencegahan pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_d'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_d'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">pengaturan sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_e'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_e'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">penggunaan bahan-bahan yang berpotensi menjadi penyebab pencemaran lingkungan<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.4 Pengelolaan Bahan Pencemar)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_f'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['k_f'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kel_pasir') !!} Pemanfataan Pasir Laut</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pelaksanaan pencegahan pencemaran dengan dokumen AMDAL/UKL-UPL/SPPL<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan persyaratan pelaksanaan kegiatan dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">memeriksa material pasir laut tidak mengandung B3<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan melakukan uji sampel material pasir laut di laboratorium terakreditasi)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['l_4'] ?? '', 'tidak') !!}</td></tr>
</table>

<table class="tbl-border" style="font-size:10pt; width:100%; margin-bottom:15px;">
    <tr><th style="width:70%;">Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Sektor Perikanan)</th><th style="width:15%;">Sesuai</th><th style="width:15%;">Tidak Sesuai</th></tr>
    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kan_kapal') !!} Kegiatan kapal perikanan</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">kondisi mesin yang berpotensi menimbulkan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan visual ada tidaknya kebocoran oli pada mesin)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">penanganan limbah oli bekas, sampah, dan/atau limbah lainnya<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.6 Penanganan Limbah Kapal)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kondisi perairan di sekitar area kapal perikanan yang diperiksa<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan melakukan pengamatan visual ada tidaknya kebocoran oli/limbah dari kapal/buangan sampah dari kapal dan mengkonfirmasi kepada nahkoda/ABK kapal)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_a_4'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kan_budidaya') !!} Kegiatan pembudidayaan ikan</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">standar pengelolaan bahan-bahan yang berpotensi menjadi penyebab pencemaran perairan<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.4 Pengelolaan Bahan Pencemar)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_b_4'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kan_olah') !!} Kegiatan pengolahan ikan</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">pengambilan sampel air di outlet/saluran pembuangan air limbah (apabila diperlukan)<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan melakukan uji sampel air di laboratorium terakreditasi)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_c_3'] ?? '', 'tidak') !!}</td></tr>

    <tr><td colspan="3">{!! cbArr($baPencemaran->jenis_usaha, 'kan_pelabuhan') !!} Kegiatan pelabuhan perikanan</td></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">hasil uji kualitas air di wilayah pelabuhan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan memeriksa hasil uji kualitas air secara berkala yang dilakukan oleh pengelola pelabuhan)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian tempat penyimpanan dan fasilitas pengisian bahan bakar dengan standar keamanan dan keselamatan lingkungan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan persyaratan AMDAL/UKL-UPL/SPPL dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_4'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pengelolaan limbah cair dan sampah dari tempat pelelangan ikan dengan standar yang ditetapkan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan persyaratan AMDAL/UKL-UPL/SPPL dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_5'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_5'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pengelolaan limbah domestik dari aktivitas di dalam pelabuhan perikanan dengan standar yang ditetapkan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan membandingkan persyaratan AMDAL/UKL-UPL/SPPL dengan kondisi eksisting)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_6'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_6'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">kesesuaian pengelolaan sampah yang berasal dari kapal dengan standar yang ditetapkan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan dan interview ada/tidaknya pengumpulan sampah dari kapal)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_7'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_7'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">API dan ABPI yang rusak telah ditempat di tempat penampungan khusus<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan langsung)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_8'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_8'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan tempat pengumpul sampah terpilah di dalam pelabuhan perikanan<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan langsung)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_9'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_9'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan tempat penampungan sampah sementara (TPS)<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan langsung)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_10'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_10'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan alat pengangkut sampah<br><span style="font-size:8pt; font-style:italic;">(diperiksa dengan pengamatan langsung)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_11'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['kan_d_11'] ?? '', 'tidak') !!}</td></tr>
</table>

<table class="tbl-border" style="font-size:10pt; width:100%; margin-bottom:15px;">
    <tr><th style="width:70%;">Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran (Kegiatan dan Usaha Lain)</th><th style="width:15%;">Sesuai</th><th style="width:15%;">Tidak Sesuai</th></tr>
    <tr><td style="padding-left:15px;">Hasil Pemeriksaan:</td><td></td><td></td></tr>
    <tr><td style="padding-left:15px;">dokumen rencana pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_1'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_1'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">ketersediaan fasilitas pencegahan pencemaran<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_2'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_2'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">sistem pengolahan dan pembuangan limbah<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.3 Sistem Pengolahan dan Pembuangan Limbah)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_3'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_3'] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="padding-left:15px;">standar pengelolaan bahan-bahan yang berpotensi menjadi penyebab pencemaran perairan<br><span style="font-size:8pt; font-style:italic;">(diperiksa melalui Form E.4 Pengelolaan Bahan Pencemar)</span></td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_4'] ?? '', 'sesuai') !!}</td><td style="text-align:center; vertical-align:middle;">{!! cb($hp['lain_4'] ?? '', 'tidak') !!}</td></tr>
</table>

<table class="tbl-border" style="font-size:10pt; width:100%; margin-bottom:15px;">
    <tr><th style="width:70%;">Hasil Akhir Kesesuaian Pelaksanaan Kegiatan Pencegahan Pencemaran</th><th style="width:15%; font-weight:normal;">{!! cb($hp['akhir_sesuai'] ?? '', 'sesuai') !!} Sesuai</th><th style="width:15%; font-weight:normal;">{!! cb($hp['akhir_sesuai'] ?? '', 'tidak') !!} Tidak sesuai</th></tr>
</table>

<div class="section-title">Dugaan Pencemaran</div>
<p>
    {!! cbBool($baPencemaran->dugaan_pencemaran_ada) !!} Ada &nbsp;&nbsp;&nbsp;&nbsp; {!! cbBoolFalse($baPencemaran->dugaan_pencemaran_ada) !!} Tidak Ada
</p>
<p>Jika ada, deskripsikan kondisi pencemaran (dari pengamatan visual, adanya bau, kekeruhan, dsb. dibandingkan dengan kondisi perairan sekitarnya didukung dengan dokumentasi (foto dan video geo tagging):</p>
<p style="border-bottom: 1px dotted #000; min-height: 20px;">{{ $orDash($baPencemaran->dugaan_pencemaran_ket) }}</p>

<p>Perkiraan luasan area yang tercemar: {{ $orDash($baPencemaran->luas_area_tercemar) }} Ha</p>

<p>Perkiraan luasan ekosistem mangrove, padang lamun, terumbu karang dan/atau populasi ikan yang terdampak pencemaran:</p>
<table style="width:100%; margin-bottom:15px;">
    <tr><td style="width:300px;">Luas Ekosistem Mangrove</td><td>{{ $orDash($baPencemaran->luas_mangrove) }} Ha</td></tr>
    <tr><td>Luas Ekosistem Padang Lamun</td><td>{{ $orDash($baPencemaran->luas_lamun) }} Ha</td></tr>
    <tr><td>Luas Ekosistem Terumbu Karang</td><td>{{ $orDash($baPencemaran->luas_terumbu_karang) }} Ha</td></tr>
    <tr><td>Luas Habitat Populasi Ikan</td><td>{{ $orDash($baPencemaran->luas_habitat_ikan) }} Ha</td></tr>
</table>

<p>Apabila ditemukan indikasi ketidakpatuhan, terjadinya pencemaran dan/atau mengakibatkan kerusakan dan/atau kerugian pada sumber daya ikan dan lingkungannya, dapat langsung dilakukan pengenaan tindakan lain menurut hukum yang bertanggung jawab terhadap pelaku usaha yang melakukan kegiatan yang tidak sesuai dengan ketentuan peraturan perundang-undangan, dalam bentuk:</p>
<table style="width:100%; font-size:10pt; margin-bottom:15px;">
    <tr><td style="width:50%;">{!! cbArr($baPencemaran->indikasi_ketidakpatuhan, 'a') !!} menghentikan kegiatan yang tidak sesuai dengan ketentuan peraturan perundangundangan;</td></tr>
    <tr><td style="width:50%;">{!! cbArr($baPencemaran->indikasi_ketidakpatuhan, 'b') !!} memaksa pelaku usaha untuk melakukan pencegahan kegiatan yang tidak sesuai dengan ketentuan peraturan perundang-undangan;</td></tr>
    <tr><td>{!! cbArr($baPencemaran->indikasi_ketidakpatuhan, 'c') !!} penyegelan; dan/atau</td></tr>
    <tr><td>{!! cbArr($baPencemaran->indikasi_ketidakpatuhan, 'd') !!} pemasangan garis Pengawas Perikanan/Polsus PWP-3-K.</td></tr>
</table>

<div class="section-title">Pengambilan sampel (bila diperlukan)</div>
<p>{!! cbBool($baPencemaran->sampel_ada) !!} Ada &nbsp;&nbsp;&nbsp;&nbsp; {!! cbBoolFalse($baPencemaran->sampel_ada) !!} Tidak Ada</p>
<table style="width:100%; margin-bottom:15px;">
    <tr><td style="width:250px;">Tanggal pengambilan sampel</td><td style="width:10px;">:</td><td>{{ $baPencemaran->sampel_tgl ? $baPencemaran->sampel_tgl->format('d-m-Y') : '-' }}</td></tr>
    <tr><td>Jumlah titik pengambilan sampel</td><td>:</td><td>{{ $orDash($baPencemaran->sampel_jumlah_titik) }} titik</td></tr>
    <tr><td>Pada koordinat</td><td>:</td><td>{{ $orDash($baPencemaran->sampel_koordinat) }}</td></tr>
    <tr><td colspan="3">Hasil uji laboratorium</td></tr>
    <tr><td>Nama laboratorium</td><td>:</td><td>{{ $orDash($baPencemaran->sampel_nama_lab) }}</td></tr>
    <tr><td>Tanggal hasil uji terbit</td><td>:</td><td>{{ $baPencemaran->sampel_lab_tgl ? $baPencemaran->sampel_lab_tgl->format('d-m-Y') : '-' }}</td></tr>
    <tr><td colspan="3">{!! cb($baPencemaran->sampel_hasil_uji, 'melampaui') !!} Melampaui baku mutu &nbsp;&nbsp;&nbsp;&nbsp; {!! cb($baPencemaran->sampel_hasil_uji, 'di_bawah') !!} Di bawah baku mutu</td></tr>
</table>

<div class="section-title">Kronologis Apabila Terjadi Pencemaran Sumber Daya Ikan dan Lingkungannya</div>
<p style="border-bottom: 1px dotted #000; min-height: 20px;">{{ $orDash($baPencemaran->kronologis) }}</p>

<div class="section-title">Kesimpulan</div>
<p>Pemenuhan Dokumen Pencegahan Pencemaran:<br>
{!! cb($baPencemaran->kesimpulan_dokumen, 'sesuai') !!} Sesuai &nbsp;&nbsp;&nbsp;&nbsp; {!! cb($baPencemaran->kesimpulan_dokumen, 'tidak_sesuai') !!} Tidak Sesuai
</p>

<p>Indikasi Pencemaran:<br>
{!! cbBool($baPencemaran->kesimpulan_indikasi_pencemaran) !!} Ada &nbsp;&nbsp;&nbsp;&nbsp; {!! cbBoolFalse($baPencemaran->kesimpulan_indikasi_pencemaran) !!} Tidak Ada
</p>

<p>Indikasi Pelanggaran:<br>
{!! cbBool($baPencemaran->kesimpulan_indikasi_pelanggaran) !!} Ada &nbsp;&nbsp;&nbsp;&nbsp; {!! cbBoolFalse($baPencemaran->kesimpulan_indikasi_pelanggaran) !!} Tidak Ada
</p>

<p>Keterangan:<br>
<span style="font-size:9pt; font-style:italic;">(beri penjelasan singkat yang diperlukan)</span><br>
{{ $orDash($baPencemaran->kesimpulan_keterangan) }}
</p>

<p style="margin-top:20px;">
    Demikian Berita Acara Hasil Pengawasan Pencemaran Sumber Daya Ikan dan Lingkungannya untuk diketahui dan dipergunakan sebagaimana mestinya.
</p>

<table class="ttd-table" style="margin-bottom: 40px;">
    <tr>
        <td>
            Pelaku Usaha<br><br><br>
            @if($ttd = $ttdSrc($baPencemaran->ttd_pelaku_usaha))
                <img src="{{ $ttd }}" style="height:60px; object-fit:contain;"><br>
            @else
                <div style="height:60px;"></div>
            @endif
            ( <u><strong>{{ strtoupper($orDash($baPencemaran->nama_pj)) }}</strong></u> )
        </td>
        <td>
            Pengawas Perikanan/Polsus PWP-3-K<br><br><br>
            @if($ttd = $ttdSrc($baPencemaran->ttd_pengawas_1))
                <img src="{{ $ttd }}" style="height:60px; object-fit:contain;"><br>
            @else
                <div style="height:60px;"></div>
            @endif
            ( <u><strong>{{ strtoupper($baPencemaran->pengawas->first()->nama ?? '..........................') }}</strong></u> )<br>
            NIP. {{ $baPencemaran->pengawas->first()->nip ?? '..........................' }}
        </td>
    </tr>
    <tr>
        <td style="padding-top: 30px;">
            Saksi 1<br><br><br>
            @if($ttd = $ttdSrc($baPencemaran->ttd_saksi_1))
                <img src="{{ $ttd }}" style="height:60px; object-fit:contain;"><br>
            @else
                <div style="height:60px;"></div>
            @endif
            ( <u><strong>.........................................</strong></u> )
        </td>
        <td style="padding-top: 30px;">
            Saksi 2<br><br><br>
            @if($ttd = $ttdSrc($baPencemaran->ttd_saksi_2))
                <img src="{{ $ttd }}" style="height:60px; object-fit:contain;"><br>
            @else
                <div style="height:60px;"></div>
            @endif
            ( <u><strong>.........................................</strong></u> )
        </td>
    </tr>
</table>

<!-- LAMPIRAN FORM E.1 - E.6 -->
<div class="page-break"></div>

@include('ba-was-prl.partials.kop-surat')

<div class="section-title">Form E.1 Kesesuaian Dokumen Rencana Pencegahan Pencemaran</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Pemeriksaan Kesesuaian Dokumen Rencana Pencegahan Pencemaran<br><span style="font-weight:normal;">Dokumen yang diperiksa: (AMDAL/UKL-UPL/SPPL)*</span></div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Kesesuaian</th></tr>
    <tr><th style="width:50px;">Ya</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Nama Pelaku Usaha</td><td style="text-align:center;">{!! cb($e1[1] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Nama Usaha/Kegiatan</td><td style="text-align:center;">{!! cb($e1[2] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Jenis Usaha/Kegiatan</td><td style="text-align:center;">{!! cb($e1[3] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[3] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">4</td><td>Lokasi Pelaksanaan Usaha/Kegiatan</td><td style="text-align:center;">{!! cb($e1[4] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[4] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">5</td><td>Keabsahan Dokumen</td><td style="text-align:center;">{!! cb($e1[5] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[5] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">6</td><td>Pelaksanaan Ketentuan Dokumen</td><td style="text-align:center;">{!! cb($e1[6] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1[6] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e1['kesimpulan'] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e1['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<br>
<div class="section-title">Form E.2 Ketersediaan Fasilitas Pencegahan Pencemaran</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Pemeriksaan Ketersediaan Fasilitas Pencegahan Pencemaran</div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Ketersediaan</th></tr>
    <tr><th style="width:50px;">Ada</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Jaringan air limbah</td><td style="text-align:center;">{!! cb($e2[1] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Sarana pengolahan limbah cair</td><td style="text-align:center;">{!! cb($e2[2] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Tempat sampah</td><td style="text-align:center;">{!! cb($e2[3] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2[3] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">4</td><td>Tempat penampungan sampah sementara</td><td style="text-align:center;">{!! cb($e2[4] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2[4] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">5</td><td>Toilet</td><td style="text-align:center;">{!! cb($e2[5] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2[5] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e2['kesimpulan'] ?? '', 'ada') !!}</td><td style="text-align:center;">{!! cb($e2['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<br>
<div class="section-title">Form E.3 Sistem Pengolahan dan Pembuangan Limbah</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Pemeriksaan Sistem Pengolahan dan Pembuangan Limbah</div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Ketersediaan/ Kesesuaian</th></tr>
    <tr><th style="width:50px;">Ya</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Persetujuan Lingkungan/Izin Lingkungan yang dimiliki</td><td style="text-align:center;">{!! cb($e3[1] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Izin pembuangan limbah cair ke laut yang dimiliki</td><td style="text-align:center;">{!! cb($e3[2] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Operasional sistem pengolahan dan pembuangan limbah</td><td style="text-align:center;">{!! cb($e3[3] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3[3] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">4</td><td>Dimensi dan kapasitas sistem pengolahan dan pembuangan limbah</td><td style="text-align:center;">{!! cb($e3[4] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3[4] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">5</td><td>Pelaporan berkala hasil sistem pengolahan dan pembuangan limbah</td><td style="text-align:center;">{!! cb($e3[5] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3[5] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e3['kesimpulan'] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e3['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<br>
<div class="section-title">Form E.4 Pengelolaan Bahan Pencemar</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Pemeriksaan Pengelolaan Bahan Pencemar</div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Ketersediaan/ Kesesuaian</th></tr>
    <tr><th style="width:50px;">Ya</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Prosedur pengelolaan bahan pencemar</td><td style="text-align:center;">{!! cb($e4[1] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e4[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Pemilahan bahan pencemar</td><td style="text-align:center;">{!! cb($e4[2] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e4[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Penanganan daur ulang bahan pencemar</td><td style="text-align:center;">{!! cb($e4[3] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e4[3] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e4['kesimpulan'] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e4['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<br>
<div class="section-title">Form E.5 Kesesuaian Dokumen PKKPRL</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Kesesuaian Dokumen PKKPRL</div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Kesesuaian</th></tr>
    <tr><th style="width:50px;">Ya</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Nama Pelaku Usaha/Kegiatan</td><td style="text-align:center;">{!! cb($e5[1] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Jenis Kegiatan</td><td style="text-align:center;">{!! cb($e5[2] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Lokasi Usaha/Kegiatan</td><td style="text-align:center;">{!! cb($e5[3] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[3] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">4</td><td>Luas Area Pemanfaatan</td><td style="text-align:center;">{!! cb($e5[4] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[4] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">5</td><td>Kesesuaian Peruntukan/Zonasi</td><td style="text-align:center;">{!! cb($e5[5] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[5] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">6</td><td>Keabsahan Dokumen</td><td style="text-align:center;">{!! cb($e5[6] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[6] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">7</td><td>Penyampaian Kewajiban Pelaporan</td><td style="text-align:center;">{!! cb($e5[7] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[7] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">8</td><td>Pemenuhan Hak dan Kewajiban</td><td style="text-align:center;">{!! cb($e5[8] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5[8] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e5['kesimpulan'] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e5['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<br>
<div class="section-title">Form E.6 Penanganan Limbah Kapal</div>
<div style="text-align:center; font-weight:bold; margin-bottom:10px;">Formulir Penanganan Limbah Kapal</div>
<table class="tbl-border" style="font-size:10pt;">
    <tr><th rowspan="2" style="width:30px;">No</th><th rowspan="2">Yang Diperiksa</th><th colspan="2">Ketersediaan</th></tr>
    <tr><th style="width:50px;">Ya</th><th style="width:50px;">Tidak</th></tr>
    <tr><td style="text-align:center;">1</td><td>Tempat penampungan sampah padat sementara</td><td style="text-align:center;">{!! cb($e6[1] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6[1] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">2</td><td>Tempat penampungan oli bekas sementara</td><td style="text-align:center;">{!! cb($e6[2] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6[2] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">3</td><td>Catatan logistik perbekalan kapal</td><td style="text-align:center;">{!! cb($e6[3] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6[3] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">4</td><td>Catatan logistik oli dan jadwal pergantian oli</td><td style="text-align:center;">{!! cb($e6[4] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6[4] ?? '', 'tidak') !!}</td></tr>
    <tr><td style="text-align:center;">5</td><td>Penanggung jawab penanganan limbah kapal</td><td style="text-align:center;">{!! cb($e6[5] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6[5] ?? '', 'tidak') !!}</td></tr>
    <tr style="font-weight:bold;"><td colspan="2" style="text-align:center;">Kesimpulan Akhir</td><td style="text-align:center;">{!! cb($e6['kesimpulan'] ?? '', 'ya') !!}</td><td style="text-align:center;">{!! cb($e6['kesimpulan'] ?? '', 'tidak') !!}</td></tr>
</table>

<table class="ttd-table" style="margin-top: 50px;">
    <tr>
        <td style="width:60%;"></td>
        <td style="width:40%;">
            Pengawas Perikanan/Polsus PWP-3-K<br><br><br>
            @if($ttd = $ttdSrc($baPencemaran->ttd_pengawas_1))
                <img src="{{ $ttd }}" style="height:60px; object-fit:contain;"><br>
            @else
                <div style="height:60px;"></div>
            @endif
            ( <u><strong>{{ strtoupper($baPencemaran->pengawas->first()->nama ?? '..........................') }}</strong></u> )<br>
            NIP. {{ $baPencemaran->pengawas->first()->nip ?? '..........................' }}
        </td>
    </tr>
</table>

</body>
</html>
