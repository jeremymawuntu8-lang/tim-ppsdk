<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaPpkRequest extends FormRequest
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
            'nomor_ba' => ['required', 'string', 'max:255', 'unique:ba_ppks,nomor_ba,' . ($this->ba_ppk ? $this->ba_ppk->id : 'null')],
            'pelaku_usaha_id' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'tanggal_pengawasan' => ['required', 'date'],
            'jam_wita' => ['nullable'],
            'lokasi' => ['nullable', 'string'],
            
            // 1. Profil
            'nama_pj' => ['nullable', 'string', 'max:255'],
            'nik_pj' => ['nullable', 'string', 'max:255'],
            'alamat_pj' => ['nullable', 'string'],
            'status_modal' => ['nullable', 'string', 'in:asing,dalam_negeri'],
            'kepemilikan_saham' => ['nullable', 'string', 'in:swasta,pemerintah'],
            'nama_saham_1' => ['nullable', 'string', 'max:255'],
            'nama_saham_2' => ['nullable', 'string', 'max:255'],
            'nama_pulau' => ['nullable', 'string', 'max:255'],
            'kategori_lokasi' => ['nullable', 'string', 'in:ppk,ppkt'],
            'jenis_usaha' => ['nullable', 'array'],

            // 2. Pemeriksaan Perizinan
            'syarat_rdtr_belum' => ['nullable', 'boolean'],
            'syarat_rdtr_non_oss' => ['nullable', 'boolean'],
            'syarat_rtr_zonasi' => ['nullable', 'boolean'],
            'syarat_pengecualian_pkkpr' => ['nullable', 'boolean'],

            'rek_ppk_ada' => ['nullable', 'boolean'],
            'rek_ppk_jenis' => ['nullable', 'string'],
            'rek_ppk_jenis_sts' => ['nullable', 'string'],
            'rek_ppk_nomor' => ['nullable', 'string'],
            'rek_ppk_nomor_sts' => ['nullable', 'string'],
            'rek_ppk_tgl' => ['nullable', 'date'],
            'rek_ppk_tgl_sts' => ['nullable', 'string'],
            'rek_ppk_penerbit' => ['nullable', 'string'],
            'rek_ppk_penerbit_sts' => ['nullable', 'string'],
            'rek_ppk_masa_berlaku' => ['nullable', 'string'],
            'rek_ppk_masa_berlaku_sts' => ['nullable', 'string'],
            'rek_ppk_jenis_kegiatan' => ['nullable', 'string'],
            'rek_ppk_jenis_kegiatan_sts' => ['nullable', 'string'],
            'rek_ppk_luas_izin' => ['nullable', 'string'],
            'rek_ppk_luas_izin_sts' => ['nullable', 'string'],
            'rek_ppk_luas_pemanfaatan' => ['nullable', 'string'],
            'rek_ppk_koordinat_izin' => ['nullable', 'string'],
            'rek_ppk_koordinat_izin_sts' => ['nullable', 'string'],
            'rek_ppk_koordinat_eksisting' => ['nullable', 'string'],

            'pkkpr_ada' => ['nullable', 'boolean'],
            'pkkpr_nomor' => ['nullable', 'string'],
            'pkkpr_tgl' => ['nullable', 'date'],
            'pkkpr_penerbit' => ['nullable', 'string'],
            'pkkpr_luas' => ['nullable', 'string'],
            'pkkpr_koordinat' => ['nullable', 'string'],

            'lingkungan_ada' => ['nullable', 'boolean'],
            'lingkungan_nomor' => ['nullable', 'string'],
            'lingkungan_tgl' => ['nullable', 'date'],
            'lingkungan_penerbit' => ['nullable', 'string'],

            'nib_ada' => ['nullable', 'boolean'],
            'nib_nomor' => ['nullable', 'string'],
            'nib_tgl' => ['nullable', 'date'],
            'nib_kbli' => ['nullable', 'string'],

            'izin_usaha_ada' => ['nullable', 'boolean'],
            'izin_usaha_nomor' => ['nullable', 'string'],
            'izin_usaha_tgl' => ['nullable', 'date'],
            'izin_usaha_penerbit' => ['nullable', 'string'],
            'izin_usaha_masa' => ['nullable', 'string'],
            'izin_usaha_jenis' => ['nullable', 'string'],
            'izin_usaha_luas' => ['nullable', 'string'],
            'izin_usaha_lokasi' => ['nullable', 'string'],
            'izin_usaha_koordinat' => ['nullable', 'string'],

            'dok_lain_ada' => ['nullable', 'boolean'],
            'dok_lain_jenis' => ['nullable', 'string'],
            'dok_lain_nomor' => ['nullable', 'string'],
            'dok_lain_tgl' => ['nullable', 'date'],
            'dok_lain_penerbit' => ['nullable', 'string'],
            'dok_lain_lokasi' => ['nullable', 'string'],

            // 3. Pemeriksaan Pemenuhan
            'pemenuhan_rth' => ['nullable', 'string'],
            'pemenuhan_rtr' => ['nullable', 'string'],
            'pemenuhan_akses' => ['nullable', 'string'],
            'pemenuhan_jenis' => ['nullable', 'string'],

            // 4 & 5. Dugaan
            'dugaan_pelanggaran_ada' => ['nullable', 'boolean'],
            'dugaan_pelanggaran_ket' => ['nullable', 'string'],
            'dugaan_kerusakan_ada' => ['nullable', 'boolean'],
            'dugaan_kerusakan_ket' => ['nullable', 'string'],

            // 6 & 7. Kesimpulan & Rekomendasi
            'kesimpulan' => ['nullable', 'string'],
            'rekomendasi_tindakan' => ['nullable', 'array'],
            'rekomendasi_lainnya' => ['nullable', 'string'],

            // Pengawas & File
            'pengawas' => ['nullable', 'array'],
            'pengawas.*.nama' => ['nullable', 'string'],
            'pengawas.*.nip' => ['nullable', 'string'],
            'pengawas.*.jabatan' => ['nullable', 'string'],
            
            'foto' => ['nullable', 'array'],
            'foto.*' => ['image', 'max:5120'],
            
            'ttd_pelaku_usaha' => ['nullable', 'string'],
            'ttd_pengawas_1' => ['nullable', 'string'],
            
            'status' => ['nullable', 'string', 'in:draft,proses,selesai,tindak_lanjut'],
        ];
    }
}
