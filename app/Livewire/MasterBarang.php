<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MBarang;

class MasterBarang extends Component
{
    public $id_barang, $kode_kategori, $nama_barang, $tipe_barang, $spesifikasi;
    public $isOpen = false;

    protected $rules = [
        'kode_kategori' => 'required|string|max:50',
        'nama_barang' => 'required|string|max:255',
        'tipe_barang' => 'required|in:habis_pakai,aset',
        'spesifikasi' => 'nullable|string',
    ];

    public function create()
    {
        $this->reset(['id_barang', 'kode_kategori', 'nama_barang', 'tipe_barang', 'spesifikasi']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['id_barang', 'kode_kategori', 'nama_barang', 'tipe_barang', 'spesifikasi']);
    }

    public function simpan()
    {
        $this->validate();
        MBarang::updateOrCreate(
            ['id_barang' => $this->id_barang],
            [
                'kode_kategori' => $this->kode_kategori,
                'nama_barang' => $this->nama_barang,
                'tipe_barang' => $this->tipe_barang,
                'spesifikasi' => $this->spesifikasi,
            ]
        );
        session()->flash('success', $this->id_barang ? 'Barang berhasil diperbarui.' : 'Barang berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $barang = MBarang::findOrFail($id);
        $this->id_barang = $barang->id_barang;
        $this->kode_kategori = $barang->kode_kategori;
        $this->nama_barang = $barang->nama_barang;
        $this->tipe_barang = $barang->tipe_barang;
        $this->spesifikasi = $barang->spesifikasi;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $barang = MBarang::findOrFail($id);
        
        // Cek Relasi: t_pengadaan_detail (id_barang), t_stok_mutasi (id_barang)
        $isUsedInPengadaan = \App\Models\TPengadaanDetail::where('id_barang', $id)->exists();
        $isUsedInStok = \App\Models\TStokMutasi::where('id_barang', $id)->exists();
        $isUsedInAset = \App\Models\TInventarisFisik::where('id_barang', $id)->exists();

        if ($isUsedInPengadaan || $isUsedInStok || $isUsedInAset) {
            session()->flash('error', 'Gagal: Barang ini sudah memiliki riwayat stok, pengadaan, atau terdaftar sebagai aset.');
            return;
        }

        $barang->delete();
        session()->flash('success', 'Barang berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.master-barang', [
            'barangs' => MBarang::orderBy('id_barang', 'desc')->get()
        ])->layout('layouts.app');
    }
}
