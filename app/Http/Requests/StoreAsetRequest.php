<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_barang' => 'required|exists:m_barang,id_barang',
            'serial_number' => 'required|string|unique:t_inventaris_fisik,serial_number',
            'id_unit_sekarang' => 'nullable|exists:m_unit_kerja,id_unit',
            'status_kondisi' => 'nullable|in:Tersedia,Terdistribusi,Rusak,Servis',
        ];
    }

    public function messages(): array
    {
        return [
            'serial_number.unique' => 'Serial Number ini sudah terdaftar di sistem. Harap gunakan yang lain.',
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_barang.exists' => 'Barang tidak ditemukan di sistem.',
        ];
    }
}
