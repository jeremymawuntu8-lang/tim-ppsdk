<?php

namespace App\Helpers;

/**
 * Helper kecil untuk mengubah tanggal/angka menjadi format kata baku
 * yang dipakai pada naskah dinas / berita acara resmi, misalnya:
 * "Jum'at tanggal Sembilan Belas bulan Juni tahun Dua Ribu Dua Puluh Enam".
 */
class Terbilang
{
    protected static array $satuan = [
        '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas',
    ];

    protected static array $namaHari = [
        0 => "Minggu", 1 => "Senin", 2 => "Selasa", 3 => "Rabu", 4 => "Kamis", 5 => "Jum'at", 6 => "Sabtu",
    ];

    protected static array $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * @param  int  $dayOfWeek  0 (Minggu) - 6 (Sabtu), sesuai Carbon::dayOfWeek
     */
    public static function namaHari(int $dayOfWeek): string
    {
        return self::$namaHari[$dayOfWeek] ?? '';
    }

    public static function namaBulan(int $month): string
    {
        return self::$namaBulan[$month] ?? '';
    }

    /**
     * Ubah angka menjadi rangkaian kata bahasa Indonesia.
     * Contoh: 19 -> "Sembilan Belas", 2026 -> "Dua Ribu Dua Puluh Enam".
     */
    public static function angka(int $n): string
    {
        $hasil = self::konversi($n);

        return trim(preg_replace('/\s+/', ' ', $hasil));
    }

    protected static function konversi(int $n): string
    {
        if ($n < 0) {
            return 'Minus ' . self::konversi(abs($n));
        }

        if ($n <= 11) {
            return self::$satuan[$n];
        }

        if ($n < 20) {
            return trim(self::konversi($n - 10) . ' Belas');
        }

        if ($n < 100) {
            return trim(self::konversi(intdiv($n, 10)) . ' Puluh ' . self::konversi($n % 10));
        }

        if ($n < 200) {
            return trim('Seratus ' . self::konversi($n - 100));
        }

        if ($n < 1000) {
            return trim(self::konversi(intdiv($n, 100)) . ' Ratus ' . self::konversi($n % 100));
        }

        if ($n < 2000) {
            return trim('Seribu ' . self::konversi($n - 1000));
        }

        if ($n < 1000000) {
            return trim(self::konversi(intdiv($n, 1000)) . ' Ribu ' . self::konversi($n % 1000));
        }

        if ($n < 1000000000) {
            return trim(self::konversi(intdiv($n, 1000000)) . ' Juta ' . self::konversi($n % 1000000));
        }

        return (string) $n;
    }

    /**
     * Format tanggal lengkap ala naskah dinas dari objek Carbon/tanggal.
     * Return array ['hari'=>, 'tanggal'=>, 'bulan'=>, 'tahun'=>] siap pakai di kalimat pembuka BA.
     */
    public static function tanggalLengkap($date): array
    {
        return [
            'hari' => self::namaHari($date->dayOfWeek),
            'tanggal' => self::angka((int) $date->format('j')),
            'bulan' => self::namaBulan((int) $date->format('n')),
            'tahun' => self::angka((int) $date->format('Y')),
        ];
    }

    /**
     * Format tanggal singkat "15 April 2023" dengan nama bulan Indonesia,
     * tanpa bergantung pada locale Carbon/PHP intl (yang mungkin tidak ter-set).
     */
    public static function tanggalSingkat(?\Carbon\Carbon $date): string
    {
        if (!$date) {
            return '-';
        }

        return $date->format('j') . ' ' . self::namaBulan((int) $date->format('n')) . ' ' . $date->format('Y');
    }
}
