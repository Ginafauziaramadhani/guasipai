<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockOpnameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tgl_opname' => 'required|date',
            'id_unit' => 'required|exists:m_unit_kerja,id_unit',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:m_barang,id_barang',
            'items.*.qty_fisik' => 'required|integer|min:0',
        ];
    }
}
