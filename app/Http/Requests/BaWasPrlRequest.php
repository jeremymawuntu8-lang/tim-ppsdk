<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaWasPrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('ba_was_prl')?->id;

        return [
            'nomor_ba' => ['nullable', 'string', 'max:100'],
            'pelaku_usaha_id' => ['required', 'string', 'max:255'],
            'tanggal_pengawasan' => ['required', 'date'],
            'tim_pengawas' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string'],
            'hasil_pengawasan' => ['nullable', 'string'],
            'kesimpulan' => ['nullable', 'string'],
            'rekomendasi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'proses', 'selesai', 'tindak_lanjut'])],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'file_ba_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'foto.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],

            // Pengawas yang Bertugas
            'no_surat_tugas' => ['nullable', 'string', 'max:255'],
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

            // Informasi Pengawasan
            'jam_wita' => ['nullable', 'string', 'max:20'],
            'nama_usaha' => ['nullable', 'string', 'max:255'],
            'titik_koordinat' => ['nullable', 'string', 'max:255'],
            'titik_koordinat_existing' => ['nullable', 'string', 'max:255'],

            // Detail Pelaku Usaha (snapshot)
            'jenis_usaha' => ['nullable', 'string', 'max:255'],
            'kbli' => ['nullable', 'string', 'max:255'],
            'luas_area' => ['nullable', 'string', 'max:100'],
            'provinsi_id' => ['nullable', 'exists:provinsis,id'],

            // Form Pengawasan
            'metode_pengamatan' => ['nullable', Rule::in(['langsung', 'tidak_langsung'])],
            'nomor_perda_rzwp3k' => ['nullable', 'string', 'max:255'],
            'nomor_pkkprl' => ['nullable', 'string', 'max:255'],
            'tgl_terbit_pkkprl' => ['nullable', 'date'],
            'status_kesesuaian_kkprl' => ['nullable', Rule::in(['sesuai', 'tidak_sesuai'])],
            'catatan_dokumen_pkkprl' => ['nullable', 'string'],
            'pemenuhan_kewajiban_pkkprl' => ['nullable', Rule::in(['terpenuhi', 'tidak_terpenuhi'])],

            // Detail KKPRL & Izin Pengelolaan
            'kkprl_instansi_penerbit' => ['nullable', 'string', 'max:255'],
            'kkprl_masa_berlaku' => ['nullable', 'string', 'max:255'],
            'izin_pengelolaan_nomor' => ['nullable', 'string', 'max:255'],
            'izin_pengelolaan_instansi_penerbit' => ['nullable', 'string'],
            'izin_pengelolaan_tanggal_penerbitan' => ['nullable', 'date'],
            'izin_pengelolaan_masa_berlaku' => ['nullable', 'string', 'max:255'],
            'kesesuaian_izin_pengelolaan' => ['nullable', Rule::in(['sesuai', 'tidak_sesuai'])],

            // Formulir Pengawasan Pemenuhan Dokumen KKPRL
            'penyampaian_laporan_tertulis' => ['nullable', Rule::in(['ada', 'tidak_ada'])],
            'catatan_laporan_tahunan' => ['nullable', 'string'],
            'dampak_pelaksanaan_pkkprl' => ['nullable', Rule::in(['ada', 'tidak_ada'])],
            'catatan_dampak_prl' => ['nullable', 'string'],

            // Informasi Pelaku Usaha
            'penanggung_jawab_usaha' => ['nullable', 'string', 'max:255'],
            'jabatan_pj_usaha' => ['nullable', 'string', 'max:255'],

            // Saksi (array dinamis)
            'saksi' => ['nullable', 'array'],
            'saksi.*.nama' => ['required_with:saksi', 'string', 'max:255'],
            'saksi.*.alamat' => ['nullable', 'string', 'max:500'],
            'saksi.*.pekerjaan' => ['nullable', 'string', 'max:255'],
            'saksi.*.tanda_tangan' => ['nullable', 'string'],

            // Pengesahan & Tanda Tangan
            'catatan_pengesahan' => ['nullable', 'string'],
            'ketua_tim_tanda_tangan' => ['nullable', 'string'],
            'pj_usaha_tanda_tangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'pelaku_usaha_id.required' => 'Pelaku usaha wajib dipilih atau diketik nama barunya.',
            'tanggal_pengawasan.required' => 'Tanggal pengawasan wajib diisi.',
            'file_ba_pdf.mimes' => 'File BA harus berformat PDF.',
            'foto.*.image' => 'Foto dokumentasi harus berupa gambar.',
            'pengawas.*.nama.required_with' => 'Nama pengawas wajib diisi.',
            'saksi.*.nama.required_with' => 'Nama saksi wajib diisi.',
        ];
    }
}
