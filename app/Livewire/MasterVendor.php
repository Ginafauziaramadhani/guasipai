<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MVendor;

class MasterVendor extends Component
{
    public $id_vendor, $nama_vendor, $alamat;
    public $isOpen = false;

    protected $rules = [
        'nama_vendor' => 'required|string|max:255',
        'alamat' => 'required|string',
    ];

    public function create()
    {
        $this->reset(['id_vendor', 'nama_vendor', 'alamat']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['id_vendor', 'nama_vendor', 'alamat']);
    }

    public function simpan()
    {
        $this->validate();
        MVendor::updateOrCreate(
            ['id_vendor' => $this->id_vendor],
            [
                'nama_vendor' => $this->nama_vendor,
                'alamat' => $this->alamat,
            ]
        );
        session()->flash('success', $this->id_vendor ? 'Vendor berhasil diperbarui.' : 'Vendor berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $vendor = MVendor::findOrFail($id);
        $this->id_vendor = $vendor->id_vendor;
        $this->nama_vendor = $vendor->nama_vendor;
        $this->alamat = $vendor->alamat;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $vendor = MVendor::findOrFail($id);
        // Check relationship: t_pengadaan (id_vendor)
        $isUsedInPengadaan = \App\Models\TPengadaan::where('id_vendor', $id)->exists();

        if ($isUsedInPengadaan) {
            session()->flash('error', 'Gagal: Vendor ini sudah pernah melakukan transaksi pengadaan.');
            return;
        }

        $vendor->delete();
        session()->flash('success', 'Vendor berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.master-vendor', [
            'vendors' => MVendor::orderBy('id_vendor', 'desc')->get()
        ])->layout('layouts.app');
    }
}
