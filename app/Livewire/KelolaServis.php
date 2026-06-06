<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TInventarisFisik;
use App\Models\TRiwayatServis;
use Illuminate\Support\Facades\DB;

class KelolaServis extends Component
{
    public $id_inventaris = '';
    public $tgl_servis = '';
    public $rincian_kerusakan = '';
    public $biaya_servis = 0;
    
    // Status baru untuk aset (setelah servis dicatat)
    public $status_kondisi_baru = 'Servis';

    public function mount()
    {
        $this->tgl_servis = date('Y-m-d');
    }

    protected $rules = [
        'id_inventaris' => 'required|exists:t_inventaris_fisik,id_inventaris',
        'tgl_servis' => 'required|date',
        'rincian_kerusakan' => 'required|string',
        'biaya_servis' => 'nullable|numeric|min:0',
        'status_kondisi_baru' => 'required|in:Tersedia,Terdistribusi,Rusak,Servis',
    ];

    public function simpan()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            TRiwayatServis::create([
                'id_inventaris' => $this->id_inventaris,
                'tgl_servis' => $this->tgl_servis,
                'rincian_kerusakan' => $this->rincian_kerusakan,
                'biaya_servis' => $this->biaya_servis ?: 0,
            ]);

            // Update status kondisi aset
            $aset = TInventarisFisik::find($this->id_inventaris);
            $aset->status_kondisi = $this->status_kondisi_baru;
            $aset->save();

            DB::commit();
            $this->reset(['id_inventaris', 'rincian_kerusakan', 'biaya_servis']);
            $this->status_kondisi_baru = 'Servis';
            $this->tgl_servis = date('Y-m-d');
            
            session()->flash('success', 'Riwayat servis berhasil dicatat dan status aset telah diperbarui menjadi: ' . $aset->status_kondisi);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal mencatat servis: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Tampilkan aset fisik yang ada (bisa dicari berdasarkan SN atau Nama)
        $asets = TInventarisFisik::with('barang')->orderBy('id_inventaris', 'desc')->get();
        
        // Tampilkan riwayat servis
        $riwayats = TRiwayatServis::with(['inventarisFisik.barang'])->orderBy('id_servis', 'desc')->get();

        return view('livewire.kelola-servis', compact('asets', 'riwayats'))->layout('layouts.app');
    }
}
