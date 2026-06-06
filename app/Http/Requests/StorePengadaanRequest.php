<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengadaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_vendor' => 'required|exists:m_vendor,id_vendor',
            'no_po' => 'required|string|max:255',
            'tgl_datang' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:m_barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'no_po.required' => 'Nomor PO wajib diisi.',
            'items.required' => 'Daftar barang tidak boleh kosong.',
            'items.min' => 'Minimal harus ada 1 barang yang diinput.',
            'items.*.jumlah.min' => 'Jumlah barang minimal 1.',
        ];
    }
}
