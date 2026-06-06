<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TDistribusiHeader;
use App\Models\TDistribusiDetail;
use App\Models\TStokMutasi;
use App\Http\Requests\StoreDistribusiRequest;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    public function store(StoreDistribusiRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // 1. Simpan Header
            $header = TDistribusiHeader::create([
                'id_unit' => $validated['id_unit'],
                'id_personel_penerima' => $validated['id_personel_penerima'],
                'tgl_distribusi' => $validated['tgl_distribusi'],
            ]);

            // 2. Looping items untuk validasi stok dan simpan detail
            foreach ($validated['items'] as $item) {
                // Cek Stok
                $stokTerakhir = TStokMutasi::where('id_barang', $item['id_barang'])
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
                
                $saldoSebelumnya = $stokTerakhir ? $stokTerakhir->saldo_stok_akhir : 0;

                // Validasi Exception jika stok kurang
                if ($item['qty_keluar'] > $saldoSebelumnya) {
                    throw new \Exception("Stok untuk barang ID {$item['id_barang']} tidak mencukupi. (Sisa: $saldoSebelumnya, Diminta: {$item['qty_keluar']})");
                }

                $saldoBaru = $saldoSebelumnya - $item['qty_keluar'];

                // Simpan Detail
                TDistribusiDetail::create([
                    'id_distribusi_h' => $header->id_distribusi_h, // PK dari header
                    'id_barang' => $item['id_barang'],
                    'qty_keluar' => $item['qty_keluar'],
                ]);

                // Catat Mutasi Keluar
                TStokMutasi::create([
                    'id_barang' => $item['id_barang'],
                    'tgl_mutasi' => now(), // Atau $validated['tgl_distribusi'] tergantung kebutuhan bisnis
                    'jenis_mutasi' => 'keluar',
                    'id_referensi_mutasi' => $header->id_distribusi_h,
                    'qty_mutasi' => $item['qty_keluar'],
                    'saldo_stok_akhir' => $saldoBaru,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Distribusi barang berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function generateBAST($id)
    {
        // Fungsi ini disiapkan untuk cetak PDF Berita Acara Serah Terima
        // Logic library mPDF atau DomPDF akan ditempatkan di sini nantinya
    }
}
