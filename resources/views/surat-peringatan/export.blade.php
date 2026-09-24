<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="33" style="text-align: center; font-weight: bold; font-size: 16px; height: 30px;">
                REKAP DATA PELAKU USAHA YANG DIBERIKAN SURAT PERINGATAN {{ $jenis ?? 'SP1' }} ({{ $jenis ?? 'SP-1' }})
            </th>
        </tr>
        <tr>
            <!-- Leave empty for spacing like original if needed, or skip -->
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">No</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">ID PENERBITAN (KUSUKA/NIB)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">SUBJEK HUKUM/ NAMA PERUSAHAAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">CONTACT PERSON</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">ALAMAT</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">JENIS PERMOHONAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">BERUSAHA / NON BERUSAHA</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">PROVINSI</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">NAMA PERAIRAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">UPT</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">DETIL KEGIATAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">LUAS (Ha) / UNIT</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">PANJANG (km)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">NOMOR KKPRL / IZIN LOKASI</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">TANGGAL PENERBITAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center; vertical-align: middle;" colspan="10">LAPORAN PELAKSANAAN KKPRL</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">SANKSI</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">PEMUTAKHIRAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">KETERANGAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">LINK DOKUMEN LAPORAN TAHUNAN</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">LINK DOKUMEN KKPRL</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">NOMOR SURAT DAN TANGGAL</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">STATUS TERKIRIM</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #f2f2f2; text-align: center; vertical-align: middle;" rowspan="2">LINK UPLOAD DATA SP</th>
        </tr>
        <tr>
            <!-- Sub-headers for Laporan -->
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">LAPORAN 1 (TGL)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">STATUS 1</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">LAPORAN 2 (TGL)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">STATUS 2</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">LAPORAN 3 (TGL)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">STATUS 3</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">LAPORAN 4 (TGL)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">STATUS 4</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">LAPORAN 5 (TGL)</th>
            <th style="font-weight: bold; border: 1px solid #000; background-color: #ffff00; text-align: center;">STATUS 5</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $sp)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $sp->id_penerbitan }}</td>
            <td style="border: 1px solid #000;">{{ $sp->pelakuUsaha ? $sp->pelakuUsaha->nama_perusahaan : ($sp->contact_person ?? '-') }}</td>
            <td style="border: 1px solid #000;">{{ $sp->contact_person }}</td>
            <td style="border: 1px solid #000;">{{ $sp->alamat }}</td>
            <td style="border: 1px solid #000;">{{ $sp->jenis_permohonan }}</td>
            <td style="border: 1px solid #000;">{{ $sp->status_berusaha }}</td>
            <td style="border: 1px solid #000;">{{ $sp->provinsi }}</td>
            <td style="border: 1px solid #000;">{{ $sp->nama_perairan }}</td>
            <td style="border: 1px solid #000;">{{ $sp->upt }}</td>
            <td style="border: 1px solid #000;">{{ $sp->detil_kegiatan }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ $sp->luas }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ $sp->panjang }}</td>
            <td style="border: 1px solid #000;">{{ $sp->nomor_kkprl }}</td>
            <td style="border: 1px solid #000;">{{ $sp->tanggal_penerbitan ? $sp->tanggal_penerbitan->format('d/m/Y') : '' }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->laporan_1_tgl ? $sp->laporan_1_tgl->format('d/m/Y') : '' }}</td>
            <td style="border: 1px solid #000;">{{ $sp->laporan_1_status }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->laporan_2_tgl ? $sp->laporan_2_tgl->format('d/m/Y') : '' }}</td>
            <td style="border: 1px solid #000;">{{ $sp->laporan_2_status }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->laporan_3_tgl ? $sp->laporan_3_tgl->format('d/m/Y') : '' }}</td>
            <td style="border: 1px solid #000;">{{ $sp->laporan_3_status }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->laporan_4_tgl ? $sp->laporan_4_tgl->format('d/m/Y') : '' }}</td>
            <td style="border: 1px solid #000;">{{ $sp->laporan_4_status }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->laporan_5_tgl ? $sp->laporan_5_tgl->format('d/m/Y') : '' }}</td>
            <td style="border: 1px solid #000;">{{ $sp->laporan_5_status }}</td>
            
            <td style="border: 1px solid #000;">{{ $sp->sanksi }}</td>
            <td style="border: 1px solid #000;">{{ $sp->pemutakhiran }}</td>
            <td style="border: 1px solid #000;">{{ $sp->keterangan }}</td>
            <td style="border: 1px solid #000;">
                @if($sp->link_dokumen_laporan_tahunan)
                    <a href="{{ $sp->link_dokumen_laporan_tahunan }}">{{ $sp->link_dokumen_laporan_tahunan }}</a>
                @endif
            </td>
            <td style="border: 1px solid #000;">
                @if($sp->link_dokumen_kkprl)
                    <a href="{{ $sp->link_dokumen_kkprl }}">{{ $sp->link_dokumen_kkprl }}</a>
                @endif
            </td>
            <td style="border: 1px solid #000;">{{ $sp->nomor_surat_dan_tanggal }}</td>
            <td style="border: 1px solid #000;">{{ $sp->status_terkirim }}</td>
            <td style="border: 1px solid #000;">
                @if($sp->link_upload_data_sp)
                    <a href="{{ $sp->link_upload_data_sp }}">{{ $sp->link_upload_data_sp }}</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
