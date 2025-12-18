<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Program;

class StoredonasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nominal' => 'required|numeric|min:1',
            'nama_donatur' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'anonim' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nominal.required' => 'Nominal donasi wajib diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
        ];
    }
}
