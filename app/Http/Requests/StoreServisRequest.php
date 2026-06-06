<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_inventaris' => 'required|exists:t_inventaris_fisik,id_inventaris',
            'rincian_kerusakan' => 'required|string',
            'biaya_servis' => 'required|numeric|min:0',
            'status_akhir' => 'required|in:Tersedia,Rusak',
        ];
    }
}
