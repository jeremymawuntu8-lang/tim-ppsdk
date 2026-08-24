<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaWasAlseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('ba_was_alse')?->id;

        return [
            'nomor_ba' => ['required', 'string', 'max:100', Rule::unique('ba_was_alses', 'nomor_ba')->ignore($id)],
            'pelaku_usaha_id' => ['nullable'],
            'provinsi_id' => ['nullable', 'exists:provinsis,id'],
            'tanggal_pengawasan' => ['required', 'date'],
            'jam_wita' => ['nullable', 'string', 'max:20'],
            'lokasi' => ['nullable', 'string'],
            'no_surat_tugas' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],

            // Ketua Tim
            'ketua_tim_nama' => ['nullable', 'string', 'max:255'],
            'ketua_tim_nip' => ['nullable', 'string', 'max:100'],
            'ketua_tim_jabatan' => ['nullable', 'string', 'max:255'],
            'ketua_tim_unit_kerja' => ['nullable', 'string', 'max:255'],

            // Anggota Pengawas (array dinamis)
            'pengawas' => ['nullable', 'array'],
            'pengawas.*.nama' => ['required_with:pengawas', 'string', 'max:255'],
            'pengawas.*.nip' => ['nullable', 'string', 'max:100'],
            'pengawas.*.jabatan' => ['nullable', 'string', 'max:255'],
            'pengawas.*.unit_kerja' => ['nullable', 'string', 'max:255'],
            'pengawas.*.tanda_tangan' => ['nullable', 'string'],

            // Kegiatan & Objek Pengawasan
            'kategori_pengawasan' => ['nullable', 'string', 'max:255'],
            'objek_pengawasan' => ['nullable', 'string', 'max:255'],

            // Identitas Pelaku Usaha
            'nama_usaha' => ['nullable', 'string', 'max:255'],
            'penanggung_jawab_usaha' => ['nullable', 'string', 'max:255'],
            'jabatan_pj_usaha' => ['nullable', 'string', 'max:255'],
            'no_identitas' => ['nullable', 'string', 'max:100'],
            'alamat_kantor' => ['nullable', 'string'],
            'alamat_kegiatan' => ['nullable', 'string'],

            // Perizinan ALSE
            'nomor_nib' => ['nullable', 'string', 'max:100'],
            'jenis_kegiatan_usaha' => ['nullable', 'string', 'max:255'],
            'penerbit_izin' => ['nullable', 'string', 'max:255'],
            'nomor_izin_alse' => ['nullable', 'string', 'max:255'],
            'tgl_terbit_izin_alse' => ['nullable', 'date'],
            'masa_berlaku_izin_alse' => ['nullable', 'string', 'max:255'],
            'nama_dokumen_lain' => ['nullable', 'string', 'max:255'],
            'nomor_dokumen_lain' => ['nullable', 'string', 'max:255'],
            'kategori_kawasan' => ['nullable', 'string', 'max:255'],

            // Pemenuhan Ketentuan
            'judul_pemenuhan_ketentuan' => ['nullable', 'string', 'max:255'],
            'debit_volume_air_laut' => ['nullable', 'string', 'max:255'],
            'kesesuaian_volume_air' => ['nullable', 'string', 'max:255'],
            'kesesuaian_koordinat_inlet' => ['nullable', 'string', 'max:255'],

            // Dugaan Pelanggaran, Analisa & Rekomendasi
            'dugaan_pelanggaran' => ['nullable', 'string', 'max:255'],
            'penjelasan_dugaan_pelanggaran' => ['nullable', 'string'],
            'analisa_pengawasan' => ['nullable', 'string'],
            'rekomendasi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'proses', 'selesai', 'tindak_lanjut'])],

            // Geografis & Files
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'titik_koordinat' => ['nullable', 'string', 'max:255'],
            'file_ba_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'foto.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],

            // Saksi (array dinamis)
            'saksi' => ['nullable', 'array'],
            'saksi.*.nama' => ['required_with:saksi', 'string', 'max:255'],
            'saksi.*.alamat' => ['nullable', 'string', 'max:500'],
            'saksi.*.pekerjaan' => ['nullable', 'string', 'max:255'],
            'saksi.*.tanda_tangan' => ['nullable', 'string'],

            // Pengesahan / Tanda Tangan
            'ketua_tim_tanda_tangan' => ['nullable', 'string'],
            'pj_usaha_tanda_tangan' => ['nullable', 'string'],
            'catatan_pengesahan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_ba.required' => 'Nomor BA wajib diisi.',
            'nomor_ba.unique' => 'Nomor BA sudah digunakan.',
            'tanggal_pengawasan.required' => 'Tanggal pengawasan wajib diisi.',
            'file_ba_pdf.mimes' => 'File BA harus berformat PDF.',
            'foto.*.image' => 'Foto dokumentasi harus berupa gambar.',
            'pengawas.*.nama.required_with' => 'Nama pengawas wajib diisi.',
            'saksi.*.nama.required_with' => 'Nama saksi wajib diisi.',
        ];
    }
}
