<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MUnitKerja;
use App\Models\TInventarisFisik;
use Illuminate\Support\Facades\DB;

class LaporanUnit extends Component
{
    public $id_unit_selected = '';

    public function render()
    {
        $units = MUnitKerja::orderBy('nama_unit')->get();
        
        $asets = [];
        $histori_distribusi = [];

        if ($this->id_unit_selected) {
            // Aset Fisik di Unit Tersebut (Berdasarkan lokasi unit saat ini)
            $asets = TInventarisFisik::with('barang')
                ->where('id_unit_sekarang', $this->id_unit_selected)
                ->get();

            // Total Semua Barang (Aset/Habis Pakai) yang pernah didistribusikan ke Unit Tersebut secara kuantitas
            $histori_distribusi = DB::table('t_distribusi_detail')
                ->join('t_distribusi_header', 't_distribusi_detail.id_distribusi_h', '=', 't_distribusi_header.id_distribusi_h')
                ->join('m_barang', 't_distribusi_detail.id_barang', '=', 'm_barang.id_barang')
                ->select(
                    'm_barang.nama_barang', 
                    'm_barang.kategori', 
                    'm_barang.satuan', 
                    DB::raw('SUM(t_distribusi_detail.qty_keluar) as total_diterima')
                )
                ->where('t_distribusi_header.id_unit', $this->id_unit_selected)
                ->groupBy('m_barang.id_barang', 'm_barang.nama_barang', 'm_barang.kategori', 'm_barang.satuan')
                ->get();
        }

        return view('livewire.laporan-unit', compact('units', 'asets', 'histori_distribusi'))->layout('layouts.app');
    }
}
