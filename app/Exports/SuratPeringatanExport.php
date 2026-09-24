<?php

namespace App\Exports;

use App\Models\SuratPeringatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class SuratPeringatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    public function collection()
    {
        return SuratPeringatan::with('pelakuUsaha')->get();
    }

    public function headings(): array
    {
        return [
            'ID PENERBITAN',
            'SUBJEK HUKUM',
            'CONTACT PERSON',
            'ALAMAT',
            'JENIS PERMOHONAN',
            'BERUSAHA / NONBERUSAHA',
            'PROVINSI',
            'NAMA PERAIRAN',
            'DETIL KEGIATAN',
            'LUAS (Ha)',
            'PANJANG (Km)',
            'NOMOR KKPRL',
            'TANGGAL PENERBITAN',
            'LAPORAN 1 (Tgl)',
            'STATUS L1',
            'LAPORAN 2 (Tgl)',
            'STATUS L2',
            'LAPORAN 3 (Tgl)',
            'STATUS L3',
            'LAPORAN 4 (Tgl)',
            'STATUS L4',
            'LAPORAN 5 (Tgl)',
            'STATUS L5',
            'SANKSI (Status Akhir)',
            'SP 1 - KETERANGAN',
            'SP 1 - NOMOR SURAT & TGL',
            'SP 1 - STATUS TERKIRIM',
            'SP 1 - LINK UPLOAD',
            'SP 2 - KETERANGAN',
            'SP 2 - NOMOR SURAT & TGL',
            'SP 2 - STATUS TERKIRIM',
            'SP 2 - LINK UPLOAD',
            'SP 3 - KETERANGAN',
            'SP 3 - NOMOR SURAT & TGL',
            'SP 3 - STATUS TERKIRIM',
            'SP 3 - LINK UPLOAD',
            'PEMUTAKHIRAN',
            'DOKUMEN LAPORAN TAHUNAN',
            'DOKUMEN KKPRL',
            'UPT',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id_penerbitan,
            $row->pelakuUsaha ? $row->pelakuUsaha->nama_perusahaan : $row->contact_person,
            $row->contact_person,
            $row->alamat,
            $row->jenis_permohonan,
            $row->status_berusaha,
            $row->provinsi,
            $row->nama_perairan,
            $row->detil_kegiatan,
            $row->luas,
            $row->panjang,
            $row->nomor_kkprl,
            $row->tanggal_penerbitan ? $row->tanggal_penerbitan->format('Y-m-d') : '',
            $row->laporan_1_tgl ? $row->laporan_1_tgl->format('Y-m-d') : '',
            $row->laporan_1_status,
            $row->laporan_2_tgl ? $row->laporan_2_tgl->format('Y-m-d') : '',
            $row->laporan_2_status,
            $row->laporan_3_tgl ? $row->laporan_3_tgl->format('Y-m-d') : '',
            $row->laporan_3_status,
            $row->laporan_4_tgl ? $row->laporan_4_tgl->format('Y-m-d') : '',
            $row->laporan_4_status,
            $row->laporan_5_tgl ? $row->laporan_5_tgl->format('Y-m-d') : '',
            $row->laporan_5_status,
            $row->sanksi,
            $row->sp1_keterangan,
            $row->sp1_nomor_surat_tgl,
            $row->sp1_status_terkirim,
            $row->sp1_link_upload,
            $row->sp2_keterangan,
            $row->sp2_nomor_surat_tgl,
            $row->sp2_status_terkirim,
            $row->sp2_link_upload,
            $row->sp3_keterangan,
            $row->sp3_nomor_surat_tgl,
            $row->sp3_status_terkirim,
            $row->sp3_link_upload,
            $row->pemutakhiran,
            $row->link_dokumen_laporan_tahunan,
            $row->link_dokumen_kkprl,
            $row->upt,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk Header Row (Baris 1)
        return [
            1    => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0d6efd'], // Blue header
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();
                $highestCol = $event->sheet->getHighestColumn();
                $range = 'A1:' . $highestCol . $highestRow;
                
                // Tambahkan Border ke semua sel
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Mewarnai grup kolom laporan dengan warna kuning pucat
                $event->sheet->getDelegate()->getStyle('N1:W' . $highestRow)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFF2CC']
                    ]
                ]);

                // Mewarnai grup kolom Sanksi/SP dengan warna merah muda
                $event->sheet->getDelegate()->getStyle('X1:AJ' . $highestRow)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFCE4D6']
                    ]
                ]);
                
                // Pastikan header tetap biru (menimpa warna background di atas)
                $event->sheet->getDelegate()->getStyle('A1:' . $highestCol . '1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0052CC']
                    ]
                ]);
            },
        ];
    }
}
