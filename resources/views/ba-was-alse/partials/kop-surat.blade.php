@php
    $logoBase64 = base64_encode(file_get_contents(public_path('images/kop-kkp.png')));
@endphp
{{-- Kop Surat: Logo kiri, Teks kanan, vertical-align:middle --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:0;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:120px; vertical-align:middle; padding:0 6px 0 0;">
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo KKP" style="width:110px; height:auto;">
        </td>
        <td style="text-align:center; vertical-align:middle; padding:0; line-height:1;">
            <div style="color:#0000FF; font-weight:bold; font-size:10.5pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0;">KEMENTERIAN KELAUTAN DAN PERIKANAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:12pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0;">DIREKTORAT JENDERAL PENGAWASAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:12pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0;">SUMBER DAYA KELAUTAN DAN PERIKANAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:13pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0;">PANGKALAN PENGAWASAN SUMBER DAYA</div>
            <div style="color:#0000FF; font-weight:bold; font-size:13pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0;">KELAUTAN DAN PERIKANAN BITUNG</div>
            <div style="color:#000000; font-size:8pt; line-height:1.25; font-family:Arial,sans-serif; margin:2px 0 0 0; padding:0;">JALAN TANDARUSA &ndash; NAEMUNDUNG, KOTA BITUNG, SULAWESI UTARA</div>
            <div style="color:#000000; font-size:8pt; line-height:1.25; font-family:Arial,sans-serif; margin:0; padding:0;">TELEPON (0438) 2235520, FAKSIMILE (0438) 2235520</div>
            <div style="color:#000000; font-size:8pt; line-height:1.25; font-family:Arial,sans-serif; margin:0; padding:0;">LAMAN <span style="color:#0000FF; text-decoration:underline;">www.kkp.go.id</span> SUREL <span style="color:#0000FF; text-decoration:underline; font-style:italic;">psdkp.bitung@kkp.go.id</span></div>
        </td>
    </tr>
</table>
{{-- Garis tebal --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:4px 0 0 0;" cellpadding="0" cellspacing="0">
    <tr><td style="border-bottom:3pt solid #000; font-size:0; line-height:0; height:0; padding:0;"></td></tr>
</table>
{{-- Garis tipis --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:1.5px 0 0 0;" cellpadding="0" cellspacing="0">
    <tr><td style="border-bottom:1pt solid #000; font-size:0; line-height:0; height:0; padding:0;"></td></tr>
</table>
{{-- Jarak sebelum konten --}}
<div style="height:12px;"></div>
