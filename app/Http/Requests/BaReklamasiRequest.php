<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaReklamasiRequest extends FormRequest
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
            'nomor_ba' => ['required', 'string', 'max:255', 'unique:ba_reklamasis,nomor_ba,' . ($this->ba_reklamasi ? $this->ba_reklamasi->id : 'null')],
            'pelaku_usaha_id' => ['nullable', 'string', 'max:255'],
            'tanggal_pengawasan' => ['required', 'date'],
            'jam_wita' => ['nullable'],
            
            // Info Penanggung Jawab
            'penanggung_jawab_usaha' => ['nullable', 'string', 'max:255'],
            'nik_pj' => ['nullable', 'string', 'max:255'],
            'alamat_pj' => ['nullable', 'string'],
            'pelaksana_reklamasi' => ['nullable', 'string', 'max:255'],
            'lokasi_reklamasi' => ['nullable', 'string'],
            'jenis_pemanfaatan_reklamasi' => ['nullable', 'string', 'max:255'],
            
            // Dokumen 1
            'kkprl_nomor_izin' => ['nullable', 'string', 'max:255'],
            'kkprl_terbit_izin' => ['nullable', 'date'],
            'kkprl_pemberi_izin' => ['nullable', 'string', 'max:255'],
            'kkprl_peruntukan' => ['nullable', 'string', 'max:255'],
            
            // Dokumen 2
            'izin_reklamasi_nomor' => ['nullable', 'string', 'max:255'],
            'izin_reklamasi_terbit' => ['nullable', 'date'],
            'izin_reklamasi_pemberi' => ['nullable', 'string', 'max:255'],
            'izin_reklamasi_peruntukan' => ['nullable', 'string', 'max:255'],
            
            // Dokumen 3
            'izin_lainnya_nomor' => ['nullable', 'string', 'max:255'],
            'izin_lainnya_terbit' => ['nullable', 'date'],
            'izin_lainnya_pemberi' => ['nullable', 'string', 'max:255'],
            'izin_lainnya_peruntukan' => ['nullable', 'string', 'max:255'],
            
            // Arrays (relasi)
            'pengawas' => ['nullable', 'array'],
            'pengawas.*.nama' => ['nullable', 'string', 'max:255'],
            'pengawas.*.nip' => ['nullable', 'string', 'max:255'],
            'pengawas.*.jabatan' => ['nullable', 'string', 'max:255'],
            'pengawas.*.unit_kerja' => ['nullable', 'string', 'max:255'],
            'pengawas.*.tanda_tangan' => ['nullable', 'string'],
            
            'foto' => ['nullable', 'array'],
            'foto.*' => ['image', 'max:5120'], // max 5MB
            
            'ttd_pelaku_usaha' => ['nullable', 'string'],
            'ttd_pengawas_1' => ['nullable', 'string'],
            'ttd_pengawas_2' => ['nullable', 'string'],
            
            'status' => ['nullable', 'string', 'in:draft,proses,selesai,tindak_lanjut'],
        ];
    }
}
