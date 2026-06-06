<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPengadaan;
use App\Models\TStokMutasi;
use App\Http\Requests\StorePengadaanRequest;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function store(StorePengadaanRequest $request)
    {
        $validated = $request->validated();

        // Mulai Transaksi Database
        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                // 1. Simpan detail pengadaan
                $pengadaan = TPengadaan::create([
                    'id_vendor' => $validated['id_vendor'],
                    'no_po' => $validated['no_po'],
                    'tgl_datang' => $validated['tgl_datang'],
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                ]);

                // 2. Kalkulasi stok akhir
                $stokTerakhir = TStokMutasi::where('id_barang', $item['id_barang'])
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
                
                $saldoSebelumnya = $stokTerakhir ? $stokTerakhir->saldo_stok_akhir : 0;
                $saldoBaru = $saldoSebelumnya + $item['jumlah'];

                // 3. Catat di tabel mutasi
                TStokMutasi::create([
                    'id_barang' => $item['id_barang'],
                    'tgl_mutasi' => now(),
                    'jenis_mutasi' => 'masuk',
                    'id_referensi_mutasi' => $validated['no_po'], 
                    'qty_mutasi' => $item['jumlah'],
                    'saldo_stok_akhir' => $saldoBaru,
                ]);
            }

            // Jika semua lancar, commit data ke tabel
            DB::commit();
            return redirect()->back()->with('success', 'Data pengadaan barang masuk berhasil disimpan dan stok telah ditambahkan.');

        } catch (\Exception $e) {
            // Jika ada satu error saja di tengah loop, batalkan seluruh penyimpanan di transaksi ini
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan pengadaan: ' . $e->getMessage()]);
        }
    }
}
