<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalPengawasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pelaku_usaha_id' => ['required', 'exists:pelaku_usahas,id'],
            'jenis_pengawasan' => ['required', Rule::in(['prl', 'alse'])],
            'tanggal_rencana' => ['required', 'date'],
            'tim_pengawas' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['belum_dilaksanakan', 'sedang_berjalan', 'selesai', 'dibatalkan'])],
            'catatan' => ['nullable', 'string'],
        ];
    }
}
