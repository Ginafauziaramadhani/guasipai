<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MVendor;

class MasterVendor extends Component
{
    public $nama_vendor;
    public $alamat;

    protected $rules = [
        'nama_vendor' => 'required|string|max:255',
        'alamat' => 'required|string',
    ];

    public function simpan()
    {
        $this->validate();
        MVendor::create([
            'nama_vendor' => $this->nama_vendor,
            'alamat' => $this->alamat,
        ]);
        $this->reset(['nama_vendor', 'alamat']);
        session()->flash('success', 'Vendor berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.master-vendor', [
            'vendors' => MVendor::orderBy('id_vendor', 'desc')->get()
        ])->layout('layouts.app');
    }
}
