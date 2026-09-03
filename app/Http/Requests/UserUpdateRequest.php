<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id;
        $user = $this->route('user');

        // Cek apakah super-admin sedang mengedit dirinya sendiri
        $isSelfEdit = $user && auth()->id() === $user->id && $user->hasRole('super-admin');

        if ($isSelfEdit) {
            // Super-admin hanya boleh mengubah nama sendiri
            return [
                'name' => ['required', 'string', 'max:255'],
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', Password::min(8)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(
                \Spatie\Permission\Models\Role::where('name', '!=', 'super-admin')->pluck('name')->toArray()
            )],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
