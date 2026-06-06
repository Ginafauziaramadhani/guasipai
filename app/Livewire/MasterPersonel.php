<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MPersonel;

class MasterPersonel extends Component
{
    public $nama_personel;
    public $jabatan;

    protected $rules = [
        'nama_personel' => 'required|string|max:255',
        'jabatan' => 'required|string|max:255',
    ];

    public function simpan()
    {
        $this->validate();
        MPersonel::create([
            'nama_personel' => $this->nama_personel,
            'jabatan' => $this->jabatan,
        ]);
        $this->reset(['nama_personel', 'jabatan']);
        session()->flash('success', 'Personel berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.master-personel', [
            'personels' => MPersonel::orderBy('id_personel', 'desc')->get()
        ])->layout('layouts.app');
    }
}
