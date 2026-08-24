<?php

namespace App\Imports;

use App\Models\JenisUsaha;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PelakuUsahaImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        $provinsi = Provinsi::firstOrCreate(['nama' => $row['provinsi']], ['kode' => 'PROV-'.uniqid()]);
        $kabupaten = Kabupaten::firstOrCreate(
            ['nama' => $row['kabupaten'], 'provinsi_id' => $provinsi->id],
            ['kode' => 'KAB-'.uniqid()]
        );
        $kecamatan = Kecamatan::firstOrCreate(
            ['nama' => $row['kecamatan'] ?? '-', 'kabupaten_id' => $kabupaten->id],
            ['kode' => 'AUTO-'.uniqid()]
        );
        $kelurahan = Kelurahan::firstOrCreate(
            ['nama' => $row['kelurahan'] ?? '-', 'kecamatan_id' => $kecamatan->id],
            ['kode' => 'AUTO-'.uniqid()]
        );
        $jenisUsaha = JenisUsaha::firstOrCreate(['nama' => $row['jenis_usaha']]);

        return new PelakuUsaha([
            'nama_perusahaan' => $row['nama_perusahaan'],
            'nomor_pkkprl' => $row['nomor_pkkprl'] ?? null,
            'jenis_usaha_id' => $jenisUsaha->id,
            'luas_pkkprl' => $row['luas_pkkprl'] ?? null,
            'provinsi_id' => $provinsi->id,
            'kabupaten_id' => $kabupaten->id,
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
            'alamat' => $row['alamat'] ?? null,
            'nama_pic' => $row['nama_pic'] ?? null,
            'nomor_hp' => $row['nomor_hp'] ?? null,
            'email' => $row['email'] ?? null,
            'status' => $row['status'] ?? 'aktif',
            'created_by' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_perusahaan' => ['required'],
            'provinsi' => ['required'],
            'kabupaten' => ['required'],
            'jenis_usaha' => ['required'],
        ];
    }
}
