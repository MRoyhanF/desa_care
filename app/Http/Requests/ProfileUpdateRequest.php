<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('pengguna')->ignore($this->user()->id)],
            'telepon' => ['nullable', 'string', 'max:15'],
            'foto'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'   => 'Nama wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.unique'    => 'Email sudah digunakan.',
            'telepon.max'     => 'Nomor telepon maksimal 15 karakter.',
            'foto.image'      => 'File harus berupa gambar.',
            'foto.mimes'      => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max'        => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
