<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TInventarisFisik;
use App\Models\TStokMutasi;
use App\Models\MBarang;
use Illuminate\Support\Facades\DB;

class DashboardPimpinan extends Component
{
    public function render()
    {
        // --- 1. Summary Cards Data ---
        $totalAset = TInventarisFisik::count();
        $totalAsetRusakServis = TInventarisFisik::whereIn('status_kondisi', ['Rusak', 'Servis'])->count();
        
        // Hitung total fisik barang Habis Pakai dari saldo akhir stok
        $habisPakaiIds = MBarang::where('kategori', 'Habis Pakai')->pluck('id_barang');
        $totalHabisPakai = 0;
        foreach ($habisPakaiIds as $id) {
            $stokTerakhir = TStokMutasi::where('id_barang', $id)->orderBy('id_mutasi', 'desc')->first();
            if ($stokTerakhir) {
                $totalHabisPakai += $stokTerakhir->saldo_stok_akhir;
            }
        }

        // --- 2. Chart 1 Data (Proporsi Kondisi Aset) ---
        $kondisiRaw = TInventarisFisik::select('status_kondisi', DB::raw('count(*) as total'))
                        ->groupBy('status_kondisi')
                        ->pluck('total', 'status_kondisi')
                        ->toArray();
        
        $chart1 = [
            'labels' => array_keys($kondisiRaw),
            'series' => array_values($kondisiRaw)
        ];

        // --- 3. Chart 2 Data (Tren Barang Masuk vs Keluar 6 Bulan Terakhir) ---
        $masukData = [];
        $keluarData = [];
        $bulanLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $date->translatedFormat('F Y'); // misal: Juni 2026
            
            $masuk = TStokMutasi::where('jenis_mutasi', 'masuk')
                        ->whereMonth('tgl_mutasi', $date->month)
                        ->whereYear('tgl_mutasi', $date->year)
                        ->sum('qty_mutasi');
            $masukData[] = (int)$masuk;
            
            $keluar = TStokMutasi::where('jenis_mutasi', 'keluar')
                        ->whereMonth('tgl_mutasi', $date->month)
                        ->whereYear('tgl_mutasi', $date->year)
                        ->sum('qty_mutasi');
            $keluarData[] = (int)$keluar;
        }

        $chart2 = [
            'labels' => $bulanLabels,
            'masuk' => $masukData,
            'keluar' => $keluarData
        ];

        return view('livewire.dashboard-pimpinan', [
            'totalAset' => $totalAset,
            'totalHabisPakai' => $totalHabisPakai,
            'totalAsetRusakServis' => $totalAsetRusakServis,
            'chart1' => $chart1,
            'chart2' => $chart2,
        ])->layout('layouts.app');
    }
}
