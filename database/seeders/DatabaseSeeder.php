<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan pemanggilan ini sangat krusial untuk mencegah
        // Foreign Key Constraint Error (Master Data wajib di-seed lebih awal).
        
        $this->call([
            UserSeeder::class,
            MasterDataSeeder::class,
            InventarisFisikSeeder::class,
            DummyTransactionSeeder::class,
        ]);
    }
}
