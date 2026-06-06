<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Dalam implementasi nyata, bisa cek role user di sini.
        // Untuk sekarang kita set true agar request bisa diproses.
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
            'kategori' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori barang wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'satuan.required' => 'Satuan barang wajib diisi.',
        ];
    }
}
