<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TInventarisFisik;
use App\Models\TRiwayatServis;
use App\Http\Requests\StoreServisRequest;
use Illuminate\Support\Facades\DB;

class ServisController extends Controller
{
    public function create(Request $request)
    {
        $aset = null;
        
        // Cari aset berdasarkan Serial Number
        if ($request->has('serial_number')) {
            $aset = TInventarisFisik::where('serial_number', $request->serial_number)->first();
            if (!$aset) {
                return redirect()->back()->withErrors(['serial_number' => 'Aset dengan Serial Number tersebut tidak ditemukan.']);
            }
        }

        // Tampilkan form (View dummy)
        return view('servis.create', compact('aset'));
    }

    public function store(StoreServisRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // 1. Catat rincian servis
            TRiwayatServis::create([
                'id_inventaris' => $validated['id_inventaris'],
                'tgl_servis' => now(),
                'rincian_kerusakan' => $validated['rincian_kerusakan'],
                'biaya_servis' => $validated['biaya_servis'],
            ]);

            // 2. Update status_kondisi di tabel fisik
            $aset = TInventarisFisik::findOrFail($validated['id_inventaris']);
            $aset->status_kondisi = $validated['status_akhir']; // 'Tersedia' atau 'Rusak'
            $aset->save();

            DB::commit();
            return redirect()->back()->with('success', 'Data servis berhasil disimpan dan status aset telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
