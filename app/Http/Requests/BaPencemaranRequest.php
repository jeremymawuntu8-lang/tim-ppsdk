<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaPencemaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Tab 1 - Utama & Pengawas
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'nomor_ba' => ['nullable', 'string', 'max:255'],
            'tanggal_pengawasan' => ['nullable', 'date'],
            'jam_wita' => ['nullable'],
            'jenis_pengawasan' => ['nullable', 'string', 'in:rutin,insidental'],
            'laporan_pengaduan_nomor' => ['nullable', 'string', 'max:255'],
            'laporan_pengaduan_tgl' => ['nullable', 'date'],
            'lokasi_pengawasan' => ['nullable', 'string'],
            'koordinat' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string', 'max:100'],
            'longitude' => ['nullable', 'string', 'max:100'],

            // Pengawas
            'pengawas' => ['nullable', 'array'],
            'pengawas.*.nama' => ['nullable', 'string'],
            'pengawas.*.nip' => ['nullable', 'string'],
            'pengawas.*.jabatan' => ['nullable', 'string'],
            'pengawas.*.unit_kerja' => ['nullable', 'string'],

            // Tab 2 - Profil Usaha
            'pelaku_usaha_id' => ['nullable', 'string', 'max:255'],
            'nama_usaha_kegiatan' => ['nullable', 'string', 'max:255'],
            'nib' => ['nullable', 'string', 'max:255'],
            'luas_darat' => ['nullable', 'string', 'max:100'],
            'luas_laut' => ['nullable', 'string', 'max:100'],
            'zona_sub_zona' => ['nullable', 'string', 'max:255'],
            'nama_pj' => ['nullable', 'string', 'max:255'],
            'nik_pj' => ['nullable', 'string', 'max:255'],
            'jabatan_pj' => ['nullable', 'string', 'max:255'],
            'alamat_kantor' => ['nullable', 'string'],
            'email_pj' => ['nullable', 'email', 'max:255'],
            'no_telp_pj' => ['nullable', 'string', 'max:50'],

            // Tab 3 - Sektor & Izin
            'jenis_usaha' => ['nullable', 'array'],
            'perizinan_dasar' => ['nullable', 'array'],
            'dokumen_pencegahan' => ['nullable', 'array'],
            'perizinan_berusaha' => ['nullable', 'array'],

            // Tab 4 - Hasil Pengawasan
            'hasil_pengawasan' => ['nullable', 'array'],

            // Tab 5 - Dugaan & Sampel
            'dugaan_pencemaran_ada' => ['nullable'],
            'dugaan_pencemaran_ket' => ['nullable', 'string'],
            'luas_area_tercemar' => ['nullable', 'string', 'max:100'],
            'luas_mangrove' => ['nullable', 'string', 'max:100'],
            'luas_lamun' => ['nullable', 'string', 'max:100'],
            'luas_terumbu_karang' => ['nullable', 'string', 'max:100'],
            'luas_habitat_ikan' => ['nullable', 'string', 'max:100'],
            'indikasi_ketidakpatuhan' => ['nullable', 'array'],
            'sampel_ada' => ['nullable'],
            'sampel_tgl' => ['nullable', 'date'],
            'sampel_jumlah_titik' => ['nullable', 'integer'],
            'sampel_koordinat' => ['nullable', 'string'],
            'sampel_nama_lab' => ['nullable', 'string', 'max:255'],
            'sampel_lab_tgl' => ['nullable', 'date'],
            'sampel_hasil_uji' => ['nullable', 'string', 'in:melampaui,di_bawah'],
            'kronologis' => ['nullable', 'string'],

            // Tab 6 - Kesimpulan & TTD
            'kesimpulan_dokumen' => ['nullable', 'string', 'in:sesuai,tidak_sesuai'],
            'kesimpulan_indikasi_pencemaran' => ['nullable'],
            'kesimpulan_indikasi_pelanggaran' => ['nullable'],
            'kesimpulan_keterangan' => ['nullable', 'string'],
            'ttd_pelaku_usaha' => ['nullable', 'string'],
            'ttd_pengawas_1' => ['nullable', 'string'],
            'ttd_saksi_1' => ['nullable', 'string'],
            'ttd_saksi_2' => ['nullable', 'string'],
            'foto' => ['nullable', 'array'],
            'foto.*' => ['image', 'max:5120'],
            'status' => ['required', 'string', 'in:draft,proses,selesai,tindak_lanjut'],

            // Tab 7 - Lampiran E
            'lampiran_e1' => ['nullable', 'array'],
            'lampiran_e2' => ['nullable', 'array'],
            'lampiran_e3' => ['nullable', 'array'],
            'lampiran_e4' => ['nullable', 'array'],
            'lampiran_e5' => ['nullable', 'array'],
            'lampiran_e6' => ['nullable', 'array'],
        ];
    }
}
