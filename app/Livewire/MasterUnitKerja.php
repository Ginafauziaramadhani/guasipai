<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MUnitKerja;

class MasterUnitKerja extends Component
{
    public $id_unit, $nama_unit;
    public $isOpen = false;

    protected $rules = [
        'nama_unit' => 'required|string|max:255'
    ];

    public function create()
    {
        $this->reset(['id_unit', 'nama_unit']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['id_unit', 'nama_unit']);
    }

    public function simpan()
    {
        $this->validate();
        MUnitKerja::updateOrCreate(
            ['id_unit' => $this->id_unit],
            ['nama_unit' => $this->nama_unit]
        );
        session()->flash('success', $this->id_unit ? 'Unit Kerja berhasil diperbarui.' : 'Unit Kerja berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $unit = MUnitKerja::findOrFail($id);
        $this->id_unit = $unit->id_unit;
        $this->nama_unit = $unit->nama_unit;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $unit = MUnitKerja::findOrFail($id);
        // Check relationships: t_inventaris_fisik (id_unit_sekarang) & m_personel (id_unit)
        $isUsedInAset = \App\Models\TInventarisFisik::where('id_unit_sekarang', $id)->exists();
        $isUsedInPersonel = \App\Models\MPersonel::where('id_unit', $id)->exists();

        if ($isUsedInAset || $isUsedInPersonel) {
            session()->flash('error', 'Gagal: Unit Kerja ini sudah digunakan di data Aset atau Personel.');
            return;
        }

        $unit->delete();
        session()->flash('success', 'Unit Kerja berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.master-unit-kerja', [
            'units' => MUnitKerja::orderBy('id_unit', 'desc')->get()
        ])->layout('layouts.app');
    }
}
