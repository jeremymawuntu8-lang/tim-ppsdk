<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelakuUsahaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('pelaku_usaha')?->id;

        return [
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'nomor_pkkprl' => ['nullable', 'string', 'max:100', Rule::unique('pelaku_usahas', 'nomor_pkkprl')->ignore($id)],
            'jenis_usaha_id' => ['required', 'exists:jenis_usahas,id'],
            'luas_pkkprl' => ['nullable', 'numeric', 'min:0'],
            'provinsi_id' => ['required', 'exists:provinsis,id'],
            'kabupaten_id' => ['required', 'exists:kabupatens,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kelurahan_id' => ['required', 'exists:kelurahans,id'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'nama_pic' => ['nullable', 'string', 'max:255'],
            'jabatan_pic' => ['nullable', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', Rule::in(['aktif', 'tidak_aktif', 'dalam_proses', 'bermasalah'])],
            'foto_lokasi' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dokumen.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'jenis_dokumen.*' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'jenis_usaha_id.required' => 'Jenis usaha wajib dipilih.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'kabupaten_id.required' => 'Kabupaten wajib dipilih.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'kelurahan_id.required' => 'Kelurahan wajib dipilih.',
            'foto_lokasi.image' => 'Foto lokasi harus berupa gambar (jpg/jpeg/png).',
            'dokumen.*.mimes' => 'Dokumen harus berformat pdf/jpg/jpeg/png.',
        ];
    }
}
