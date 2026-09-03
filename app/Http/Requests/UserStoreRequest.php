<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(
                \Spatie\Permission\Models\Role::where('name', '!=', 'super-admin')->pluck('name')->toArray()
            )],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
