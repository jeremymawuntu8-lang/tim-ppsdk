<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Sulawesi Utara' => [
                'Kota Bitung' => ['Madidir' => ['Madidir Ure', 'Madidir Weru'], 'Aertembaga' => ['Aertembaga Satu', 'Aertembaga Dua']],
                'Kota Manado' => ['Wenang' => ['Wenang Selatan', 'Wenang Utara'], 'Sario' => ['Sario Utara', 'Sario Tumpaan']],
            ],
            'Sulawesi Tengah' => [
                'Kabupaten Morowali' => ['Bungku Timur' => ['Bahomotefe', 'Lahuafu'], 'Bungku Tengah' => ['Puungkoilu']],
                'Kabupaten Donggala' => ['Banawa' => ['Loli Oge'], 'Banawa Tengah' => ['Towale']],
            ],
            'Sulawesi Tenggara' => [
                'Kabupaten Konawe Selatan' => ['Moramo Utara' => ['Wawatu', 'Panambea Barata']],
                'Kabupaten Konawe' => ['Anggaberi' => ['Anggaberi Satu']],
            ],
            'Sulawesi Selatan' => [
                'Kabupaten Takalar' => ['Mangarabombang' => ['Topejawa']],
            ],
            'Sulawesi Barat' => [
                'Kabupaten Pasangkayu' => ['Pasangkayu' => ['Ako'], 'Sarudu' => ['Doda']],
            ],
            'Gorontalo' => [
                'Kabupaten Boalemo' => ['Tilamuta' => ['Tilamuta Barat']],
            ],
        ];

        $provinsiCounter = 0;
        $kabupatenCounter = 0;
        $kecamatanCounter = 0;
        $kelurahanCounter = 0;

        foreach ($data as $provinsiNama => $kabupatens) {
            $provinsiCounter++;
            $provinsi = Provinsi::create([
                'kode' => 'PROV-'.str_pad($provinsiCounter, 2, '0', STR_PAD_LEFT),
                'nama' => $provinsiNama,
            ]);

            foreach ($kabupatens as $kabupatenNama => $kecamatans) {
                $kabupatenCounter++;
                $kabupaten = Kabupaten::create([
                    'provinsi_id' => $provinsi->id,
                    'kode' => 'KAB-'.str_pad($kabupatenCounter, 3, '0', STR_PAD_LEFT),
                    'nama' => $kabupatenNama,
                ]);

                foreach ($kecamatans as $kecamatanNama => $kelurahans) {
                    $kecamatanCounter++;
                    $kecamatan = Kecamatan::create([
                        'kabupaten_id' => $kabupaten->id,
                        'kode' => 'KEC-'.str_pad($kecamatanCounter, 3, '0', STR_PAD_LEFT),
                        'nama' => $kecamatanNama,
                    ]);

                    foreach ($kelurahans as $kelurahanNama) {
                        $kelurahanCounter++;
                        Kelurahan::create([
                            'kecamatan_id' => $kecamatan->id,
                            'kode' => 'KEL-'.str_pad($kelurahanCounter, 3, '0', STR_PAD_LEFT),
                            'nama' => $kelurahanNama,
                        ]);
                    }
                }
            }
        }
    }
}
