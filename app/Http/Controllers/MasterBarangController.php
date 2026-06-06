<?php

namespace App\Http\Controllers;

use App\Models\MBarang;
use App\Http\Requests\StoreBarangRequest;
use Illuminate\Http\Request;

class MasterBarangController extends Controller
{
    public function index()
    {
        $barangs = MBarang::orderBy('id_barang', 'desc')->get();
        return view('barang.index', compact('barangs'));
    }

    public function store(StoreBarangRequest $request)
    {
        // FormRequest secara otomatis melakukan validasi.
        // Jika form tidak valid, otomatis redirect back() dengan error.
        
        // Jika valid, eksekusi penyimpanan menggunakan Eloquent
        MBarang::create($request->validated());

        // Redirect dengan flash message
        return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan');
    }
}
