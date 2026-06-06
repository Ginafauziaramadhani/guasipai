<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TPengadaan;
use App\Models\TStokMutasi;
use App\Models\TDistribusiHeader;

class DummyTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Asumsi Vendor ID 1 (PT Maju Bersama), Barang ID 3 (Seragam Satpam)
        $no_po = 'PO/2026/06/001';
        $qty = 100;
        $id_barang = 3;

        TPengadaan::create([
            'id_vendor' => 1,
            'no_po' => $no_po,
            'tgl_datang' => now()->subDays(5),
            'id_barang' => $id_barang,
            'jumlah' => $qty,
        ]);

        TStokMutasi::create([
            'id_barang' => $id_barang,
            'tgl_mutasi' => now()->subDays(5),
            'jenis_mutasi' => 'masuk',
            'id_referensi_mutasi' => $no_po,
            'qty_mutasi' => $qty,
            'saldo_stok_akhir' => $qty,
        ]);

        // Buat dummy distribusi header bulan ini untuk mengisi Dashboard Pimpinan
        TDistribusiHeader::create([
            'id_unit' => 1,
            'id_personel_penerima' => 1,
            'tgl_distribusi' => now(),
        ]);
    }
}
