<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:200',
            'nim/nrp' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:200',
            'status' => 'required|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama member wajib diisi.',
            'nama.max' => 'Nama member maksimal 200 karakter.',
            'nim/nrp.required' => 'NIM/NRP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'status_id.required' => 'Status wajib dipilih.',
        ];
    }
}
