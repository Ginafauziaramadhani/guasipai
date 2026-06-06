<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MBarang;
use App\Models\MUnitKerja;
use App\Models\TInventarisFisik;

class RegistrasiAset extends Component
{
    public $id_barang;
    public $serial_number;
    public $id_unit_sekarang;
    public $status_kondisi = 'Tersedia';

    protected $rules = [
        'id_barang' => 'required|exists:m_barang,id_barang',
        'serial_number' => 'required|string|unique:t_inventaris_fisik,serial_number',
        'id_unit_sekarang' => 'nullable|exists:m_unit_kerja,id_unit',
        'status_kondisi' => 'required|in:Tersedia,Terdistribusi,Rusak,Servis',
    ];

    protected $messages = [
        'serial_number.unique' => 'Serial Number ini sudah terdaftar di sistem. Harap periksa fisik barang.',
        'id_barang.required' => 'Pilih barang master terlebih dahulu.',
    ];

    public function simpan()
    {
        $this->validate();

        TInventarisFisik::create([
            'id_barang' => $this->id_barang,
            'serial_number' => strtoupper($this->serial_number),
            'id_unit_sekarang' => $this->id_unit_sekarang ?: null,
            'status_kondisi' => $this->status_kondisi,
        ]);

        $this->reset(['id_barang', 'serial_number', 'id_unit_sekarang']);
        $this->status_kondisi = 'Tersedia';

        session()->flash('success', 'Aset fisik dengan Serial Number berhasil diregistrasi.');
    }

    public function render()
    {
        // Hanya ambil barang yang kategorinya 'Aset'
        $barangs = MBarang::where('kategori', 'Aset')->orderBy('nama_barang')->get();
        $units = MUnitKerja::orderBy('nama_unit')->get();
        $asets = TInventarisFisik::with(['barang', 'unitKerja'])->orderBy('id_inventaris', 'desc')->get();

        return view('livewire.registrasi-aset', compact('barangs', 'units', 'asets'))->layout('layouts.app');
    }
}
