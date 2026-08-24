<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px 6px; }
        th { background: #0F4C81; color: #fff; }
        h3 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; margin-top: 0; color: #555; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; color: #fff; font-size: 9px; margin: 1px; }
        .bg-primary { background: #0d6efd; }
        .bg-info { background: #0dcaf0; color: #000; }
        .bg-success { background: #198754; }
        .bg-warning { background: #ffc107; color: #000; }
        .bg-danger { background: #dc3545; }
    </style>
</head>
<body>
    <h3>DATA PELAKU USAHA</h3>
    <p class="sub">Sistem Informasi Pengawasan Sumber Daya Kelautan - TIM IPSDK</p>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Perusahaan</th><th>Jenis Pengawasan</th><th>Jenis Usaha</th>
                <th>Wilayah</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                @php
                    $badges = [];
                    if ($row->ba_was_prls_exists) $badges[] = '<span class="badge bg-primary">BA WAS PRL</span>';
                    if ($row->ba_was_alses_exists) $badges[] = '<span class="badge bg-info">BA WAS ALSE</span>';
                    if ($row->ba_reklamasis_exists) $badges[] = '<span class="badge bg-success">BA REKLAMASI</span>';
                    if ($row->ba_ppks_exists) $badges[] = '<span class="badge bg-warning">BA PPK</span>';
                    if ($row->ba_pencemarans_exists) $badges[] = '<span class="badge bg-danger">BA PENCEMARAN</span>';

                    $wilayah = '';
                    if ($row->kabupaten_id || $row->provinsi_id) {
                        $kab = $row->kabupaten->nama ?? '';
                        $prov = $row->provinsi->nama ?? '';
                        $wilayah = trim($kab . ($kab && $prov ? ', ' : '') . $prov);
                    }
                    if (!$wilayah) $wilayah = $row->alamat ?: '-';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nama_perusahaan }}</td>
                    <td>{!! !empty($badges) ? implode(' ', $badges) : '-' !!}</td>
                    <td>{{ $row->jenisUsaha->nama ?? '-' }}</td>
                    <td>{{ $wilayah }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$row->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
