<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TInventarisFisik;
use App\Models\TDistribusiHeader;
use App\Models\TStockOpname;

class LaporanController extends Controller
{
    public function dashboard()
    {
        // 1. Tarik data agregat yang ringan untuk monitoring
        $totalAset = TInventarisFisik::count();
        $totalAsetRusak = TInventarisFisik::where('status_kondisi', 'Rusak')->count();
        
        $distribusiBulanIni = TDistribusiHeader::whereMonth('tgl_distribusi', now()->month)
                                ->whereYear('tgl_distribusi', now()->year)
                                ->count();
        
        $selisihOpnameTerakhir = TStockOpname::orderBy('tgl_opname', 'desc')
                                    ->take(10)
                                    ->get();

        // 2. Return data ke view Pimpinan
        // (View akan dibuat terpisah)
        return view('laporan.dashboard', compact(
            'totalAset', 
            'totalAsetRusak', 
            'distribusiBulanIni', 
            'selisihOpnameTerakhir'
        ));
    }
}
