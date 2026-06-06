<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MUnitKerja;
use App\Models\MPersonel;
use App\Models\MVendor;
use App\Models\MBarang;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Unit Kerja
        MUnitKerja::insert([
            ['nama_unit' => 'Cabang Jakarta'],
            ['nama_unit' => 'Cabang Bandung'],
            ['nama_unit' => 'Pusat'],
        ]);

        // 2. Personel
        MPersonel::insert([
            ['nama_personel' => 'Budi Santoso', 'jabatan' => 'Danru Satpam'],
            ['nama_personel' => 'Andi Wijaya', 'jabatan' => 'Anggota Satpam'],
            ['nama_personel' => 'Siti Aminah', 'jabatan' => 'Staff Administrasi'],
        ]);

        // 3. Vendor
        MVendor::insert([
            ['nama_vendor' => 'PT. Maju Bersama', 'alamat' => 'Jl. Kebon Kacang No. 10, Jakarta Pusat'],
            ['nama_vendor' => 'CV. Trijaya Equip', 'alamat' => 'Jl. Braga No. 15, Bandung'],
        ]);

        // 4. Master Barang
        MBarang::insert([
            ['kategori' => 'Aset', 'nama_barang' => 'Handy Talky Motorola', 'satuan' => 'Unit'],
            ['kategori' => 'Aset', 'nama_barang' => 'Metal Detector Garrett', 'satuan' => 'Unit'],
            ['kategori' => 'Habis Pakai', 'nama_barang' => 'Seragam Satpam (PDH)', 'satuan' => 'Stel'],
            ['kategori' => 'Habis Pakai', 'nama_barang' => 'Sepatu PDL', 'satuan' => 'Pasang'],
            ['kategori' => 'Habis Pakai', 'nama_barang' => 'Borgol Besi', 'satuan' => 'Pcs'],
        ]);
    }
}
