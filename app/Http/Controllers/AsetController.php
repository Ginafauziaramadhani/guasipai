<?php

namespace App\Http\Controllers;

use App\Models\TInventarisFisik;
use App\Http\Requests\StoreAsetRequest;

class AsetController extends Controller
{
    public function store(StoreAsetRequest $request)
    {
        $data = $request->validated();
        
        // Tetapkan default status jika tidak diisi dari form
        if (!isset($data['status_kondisi'])) {
            $data['status_kondisi'] = 'Tersedia';
        }

        TInventarisFisik::create($data);

        return redirect()->back()->with('success', 'Aset fisik baru berhasil diregistrasi.');
    }
}
