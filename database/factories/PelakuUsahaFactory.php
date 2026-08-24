<?php

namespace Database\Factories;

use App\Models\JenisUsaha;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelakuUsahaFactory extends Factory
{
    protected $model = PelakuUsaha::class;

    public function definition(): array
    {
        $provinsi = Provinsi::inRandomOrder()->first() ?? Provinsi::factory();
        $kabupaten = Kabupaten::where('provinsi_id', $provinsi->id)->inRandomOrder()->first();
        $kecamatan = Kecamatan::where('kabupaten_id', $kabupaten->id)->inRandomOrder()->first();
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatan->id)->inRandomOrder()->first();

        return [
            'nama_perusahaan' => 'PT. '.$this->faker->company(),
            'nomor_pkkprl' => strtoupper($this->faker->bothify('##########??######')),
            'jenis_usaha_id' => JenisUsaha::inRandomOrder()->first()?->id ?? JenisUsaha::factory(),
            'luas_pkkprl' => $this->faker->randomFloat(2, 0.1, 10),
            'provinsi_id' => $provinsi->id,
            'kabupaten_id' => $kabupaten->id,
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
            'alamat' => $this->faker->address(),
            'latitude' => $this->faker->latitude(-2.5, 2.5),
            'longitude' => $this->faker->longitude(120, 126),
            'nama_pic' => $this->faker->name(),
            'jabatan_pic' => $this->faker->jobTitle(),
            'nomor_hp' => $this->faker->numerify('08##########'),
            'email' => $this->faker->companyEmail(),
            'status' => $this->faker->randomElement(['aktif', 'tidak_aktif', 'dalam_proses', 'bermasalah']),
        ];
    }
}
