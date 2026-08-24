@php
    $logoBase64 = base64_encode(file_get_contents(public_path('images/kop-kkp.png')));
@endphp
{{-- Kop Surat: Logo kiri, Teks kanan, vertical-align:middle --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:0;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:130px; vertical-align:middle; padding:0 15px 0 10px;">
            <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo KKP" style="width:125px; height:auto;">
        </td>
        <td style="text-align:center; vertical-align:middle; padding:0; line-height:1;">
            <div style="color:#0000FF; font-weight:bold; font-size:13pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0; letter-spacing:0.5px;">KEMENTERIAN KELAUTAN DAN PERIKANAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:14.5pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0; letter-spacing:0.5px;">DIREKTORAT JENDERAL PENGAWASAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:14.5pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0; letter-spacing:0.5px;">SUMBER DAYA KELAUTAN DAN PERIKANAN</div>
            <div style="color:#0000FF; font-weight:bold; font-size:14.5pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0; letter-spacing:0.5px;">PANGKALAN PENGAWASAN SUMBER DAYA</div>
            <div style="color:#0000FF; font-weight:bold; font-size:14.5pt; line-height:1.15; font-family:Arial,sans-serif; margin:0; padding:0; letter-spacing:0.5px;">KELAUTAN DAN PERIKANAN BITUNG</div>
            <div style="color:#000000; font-size:9pt; line-height:1.2; font-family:Arial,sans-serif; margin:3px 0 0 0; padding:0;">JALAN TANDARUSA &ndash; NAEMUNDUNG, KOTA BITUNG, SULAWESI UTARA</div>
            <div style="color:#000000; font-size:9pt; line-height:1.2; font-family:Arial,sans-serif; margin:0; padding:0;">TELEPON (0438) 2235520, FAKSIMILE (0438) 2235520</div>
            <div style="color:#000000; font-size:9pt; line-height:1.2; font-family:Arial,sans-serif; margin:0; padding:0;">LAMAN <span style="color:#0000FF; text-decoration:underline;">www.kkp.go.id</span> SUREL <span style="color:#0000FF; text-decoration:underline; font-style:italic;">psdkp.bitung@kkp.go.id</span></div>
        </td>
    </tr>
</table>
{{-- Garis tebal --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:3px 0 0 0;" cellpadding="0" cellspacing="0">
    <tr><td style="border-bottom:2.5pt solid #000; font-size:0; line-height:0; height:0; padding:0;"></td></tr>
</table>
{{-- Garis tipis --}}
<table style="width:100%; border-collapse:collapse; padding:0; margin:1.5px 0 0 0;" cellpadding="0" cellspacing="0">
    <tr><td style="border-bottom:1pt solid #000; font-size:0; line-height:0; height:0; padding:0;"></td></tr>
</table>
{{-- Jarak sebelum konten --}}
<div style="height:10px;"></div>