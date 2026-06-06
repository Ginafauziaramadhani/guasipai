<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistribusiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_unit' => 'required|exists:m_unit_kerja,id_unit',
            'id_personel_penerima' => 'required|exists:m_personel,id_personel',
            'tgl_distribusi' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:m_barang,id_barang',
            'items.*.qty_keluar' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Minimal pilih 1 barang untuk didistribusikan.',
            'items.*.qty_keluar.min' => 'Jumlah barang keluar minimal 1.',
        ];
    }
}
