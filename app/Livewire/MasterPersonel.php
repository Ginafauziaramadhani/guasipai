<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MPersonel;

class MasterPersonel extends Component
{
    public $id_personel, $nama_personel, $jabatan;
    public $isOpen = false;

    protected $rules = [
        'nama_personel' => 'required|string|max:255',
        'jabatan' => 'required|string|max:255',
    ];

    public function create()
    {
        $this->reset(['id_personel', 'nama_personel', 'jabatan']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['id_personel', 'nama_personel', 'jabatan']);
    }

    public function simpan()
    {
        $this->validate();
        MPersonel::updateOrCreate(
            ['id_personel' => $this->id_personel],
            [
                'nama_personel' => $this->nama_personel,
                'jabatan' => $this->jabatan,
            ]
        );
        session()->flash('success', $this->id_personel ? 'Personel berhasil diperbarui.' : 'Personel berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $personel = MPersonel::findOrFail($id);
        $this->id_personel = $personel->id_personel;
        $this->nama_personel = $personel->nama_personel;
        $this->jabatan = $personel->jabatan;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $personel = MPersonel::findOrFail($id);
        // Check relationship: t_distribusi_header (id_personel)
        $isUsedInDistribusi = \App\Models\TDistribusiHeader::where('id_personel', $id)->exists();

        if ($isUsedInDistribusi) {
            session()->flash('error', 'Gagal: Personel ini sudah pernah menerima distribusi barang.');
            return;
        }

        $personel->delete();
        session()->flash('success', 'Personel berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.master-personel', [
            'personels' => MPersonel::orderBy('id_personel', 'desc')->get()
        ])->layout('layouts.app');
    }
}
