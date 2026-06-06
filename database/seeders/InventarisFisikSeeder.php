<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TInventarisFisik;

class InventarisFisikSeeder extends Seeder
{
    public function run(): void
    {
        // Berdasarkan urutan MasterDataSeeder:
        // ID 1 = Handy Talky Motorola
        // ID 2 = Metal Detector Garrett
        
        TInventarisFisik::insert([
            [
                'id_barang' => 1,
                'serial_number' => 'HT-MOTO-001',
                'id_unit_sekarang' => 1, // Cabang Jakarta
                'status_kondisi' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_barang' => 1,
                'serial_number' => 'HT-MOTO-002',
                'id_unit_sekarang' => 2, // Cabang Bandung
                'status_kondisi' => 'Terdistribusi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_barang' => 2,
                'serial_number' => 'MD-GARRETT-001',
                'id_unit_sekarang' => 3, // Pusat
                'status_kondisi' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
