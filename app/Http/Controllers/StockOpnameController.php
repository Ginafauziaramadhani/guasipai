<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MBarang;
use App\Models\TStokMutasi;
use App\Models\TStockOpname;
use App\Http\Requests\StoreStockOpnameRequest;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        // Tarik semua barang beserta stok mutasi terakhirnya
        $barangs = MBarang::all()->map(function ($barang) {
            $mutasiTerakhir = TStokMutasi::where('id_barang', $barang->id_barang)
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
            $barang->qty_sistem = $mutasiTerakhir ? $mutasiTerakhir->saldo_stok_akhir : 0;
            return $barang;
        });

        // Dummy return view
        return view('opname.index', compact('barangs'));
    }

    public function store(StoreStockOpnameRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                // 1. Ambil qty_sistem
                $stokTerakhir = TStokMutasi::where('id_barang', $item['id_barang'])
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
                $qty_sistem = $stokTerakhir ? $stokTerakhir->saldo_stok_akhir : 0;

                // 2. Hitung selisih otomatis
                $qty_selisih = $item['qty_fisik'] - $qty_sistem;

                // 3. Simpan ke t_stock_opname
                $opname = TStockOpname::create([
                    'id_barang' => $item['id_barang'],
                    'id_unit' => $validated['id_unit'],
                    'tgl_opname' => $validated['tgl_opname'],
                    'qty_fisik' => $item['qty_fisik'],
                    'qty_sistem' => $qty_sistem,
                    'qty_selisih' => $qty_selisih,
                ]);

                // 4. (Opsional) Penyesuaian stok jika selisih != 0
                if ($qty_selisih !== 0) {
                    TStokMutasi::create([
                        'id_barang' => $item['id_barang'],
                        'tgl_mutasi' => now(),
                        'jenis_mutasi' => 'penyesuaian',
                        'id_referensi_mutasi' => $opname->id_opname,
                        'qty_mutasi' => abs($qty_selisih),
                        'saldo_stok_akhir' => $item['qty_fisik'], // Stok menjadi sama dengan perhitungan fisik
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Stock Opname berhasil disimpan dan stok telah disesuaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memproses Stock Opname: ' . $e->getMessage()]);
        }
    }
}
