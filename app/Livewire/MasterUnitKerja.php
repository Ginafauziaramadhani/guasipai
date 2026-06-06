<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MUnitKerja;

class MasterUnitKerja extends Component
{
    public $nama_unit;

    protected $rules = [
        'nama_unit' => 'required|string|max:255'
    ];

    public function simpan()
    {
        $this->validate();
        MUnitKerja::create(['nama_unit' => $this->nama_unit]);
        $this->reset('nama_unit');
        session()->flash('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.master-unit-kerja', [
            'units' => MUnitKerja::orderBy('id_unit', 'desc')->get()
        ])->layout('layouts.app');
    }
}
