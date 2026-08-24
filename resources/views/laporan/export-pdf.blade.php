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
    </style>
</head>
<body>
    <h3>DATA PELAKU USAHA</h3>
    <p class="sub">Sistem Informasi Pengawasan Sumber Daya Kelautan - TIM IPSDK</p>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Perusahaan</th><th>Nomor PKKPRL</th><th>Jenis Usaha</th>
                <th>Provinsi</th><th>Kabupaten</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nama_perusahaan }}</td>
                    <td>{{ $row->nomor_pkkprl ?? '-' }}</td>
                    <td>{{ $row->jenisUsaha->nama ?? '-' }}</td>
                    <td>{{ $row->provinsi->nama ?? '-' }}</td>
                    <td>{{ $row->kabupaten->nama ?? '-' }}</td>
                    <td>{{ ucwords(str_replace('_',' ',$row->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
