<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengawasanTidakLangsungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumsikan otorisasi ditangani di controller/middleware
    }

    public function rules(): array
    {
        return [
            'nomor' => ['nullable', 'string', 'max:255'],
            'nama_unit_kerja' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'pelaku_usaha_id' => ['nullable', 'string', 'max:255'],
            'kegiatan_usaha' => ['nullable', 'string', 'max:255'],
            'pelapor_sumber' => ['nullable', 'string', 'max:255'],
            'tanggal_laporan' => ['nullable', 'date'],
            'tanggal_telaah' => ['nullable', 'date'],
            'hasil_telaah_penjelasan' => ['nullable', 'string'],
            'rekomendasi' => ['nullable', 'string'],
            'kepala_upt_nama' => ['nullable', 'string', 'max:255'],
            'kepala_upt_ttd' => ['nullable', 'string'], // base64
            'status' => ['required', 'string', 'in:proses,selesai,tindak_lanjut'],
            
            // Validation for dynamic table Kesesuaian
            'kesesuaian' => ['nullable', 'array'],
            'kesesuaian.*.alokasi_ruang' => ['nullable', 'string'],
            'kesesuaian.*.kegiatan' => ['nullable', 'string'],
            'kesesuaian.*.kesesuaian' => ['nullable', 'in:diperbolehkan,tidak_diperbolehkan,diperbolehkan_dengan_izin'],
            'kesesuaian.*.keterangan' => ['nullable', 'string'],

            // Validation for Images (File uploads)
            'gambar_1' => ['nullable', 'file', 'image', 'max:5120'], // Pengamatan Lokasi berdasarkan Citra Google Earth
            'gambar_2' => ['nullable', 'file', 'image', 'max:5120'], // Pengamatan Lokasi berdasarkan Citra Google Earth time series
            'gambar_3' => ['nullable', 'file', 'image', 'max:5120'], // Overlay Basemap dengan Garis Pantai
            'gambar_4' => ['nullable', 'file', 'image', 'max:5120'], // Menggunakan citra sentinel
            'gambar_5' => ['nullable', 'file', 'image', 'max:5120'], // Delineasi area indikasi pelanggaran
            'gambar_6' => ['nullable', 'file', 'image', 'max:5120'], // Overlay dengan peta sebaran ekosistem laut
            'gambar_7' => ['nullable', 'file', 'image', 'max:5120'], // Overlay dengan RTR/RZWP-3-K
            'gambar_8' => ['nullable', 'file', 'image', 'max:5120'], // Overlay dengan perizinan yang ada
        ];
    }
}
