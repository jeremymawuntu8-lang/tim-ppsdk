<?php

namespace App\Exports;

use App\Models\PelakuUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelakuUsahaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected array $filters = [])
    {
    }

    public function collection()
    {
        return PelakuUsaha::with(['jenisUsaha', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])
            ->withExists(['baWasPrls', 'baWasAlses', 'baReklamasis', 'baPpks', 'baPencemarans'])
            ->filter($this->filters)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Perusahaan', 'Jenis Pengawasan', 'Jenis Usaha',
            'Provinsi', 'Kabupaten', 'Alamat',
            'Nama PIC', 'Jabatan PIC', 'Nomor HP', 'Email', 'Status',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $jenisPengawasan = [];
        if ($row->ba_was_prls_exists) $jenisPengawasan[] = 'BA WAS PRL';
        if ($row->ba_was_alses_exists) $jenisPengawasan[] = 'BA WAS ALSE';
        if ($row->ba_reklamasis_exists) $jenisPengawasan[] = 'BA REKLAMASI';
        if ($row->ba_ppks_exists) $jenisPengawasan[] = 'BA PPK';
        if ($row->ba_pencemarans_exists) $jenisPengawasan[] = 'BA PENCEMARAN';

        return [
            $no,
            $row->nama_perusahaan,
            !empty($jenisPengawasan) ? implode(', ', $jenisPengawasan) : '-',
            $row->jenisUsaha->nama ?? '-',
            $row->provinsi->nama ?? '-',
            $row->kabupaten->nama ?? '-',
            $row->alamat ?? '-',
            $row->nama_pic,
            $row->jabatan_pic,
            $row->nomor_hp,
            $row->email,
            ucwords(str_replace('_', ' ', $row->status)),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
