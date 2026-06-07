<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\RoleAdmin;
use App\Http\Middleware\RolePimpinan;
use App\Livewire\CreatePengadaan;
use App\Livewire\DashboardPimpinan;
use App\Livewire\AdminDashboard;
use App\Livewire\MasterUnitKerja;
use App\Livewire\MasterPersonel;
use App\Livewire\MasterVendor;
use App\Livewire\CreateDistribusi;
use App\Livewire\RegistrasiAset;
use App\Livewire\KelolaServis;
use App\Livewire\StockOpname;
use App\Livewire\LaporanUnit;
use App\Livewire\UserManagement;

Route::get('/', function () {
    return redirect()->route('login');
});

// Activity 1: Login & Logout Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dilindungi oleh middleware 'auth'
Route::middleware('auth')->group(function () {
    
    // Group khusus Admin
    Route::middleware([RoleAdmin::class])->group(function () {
        Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin');

        // Activity 2: Kelola Data Master (m_barang, m_unit_kerja, dll)
        Route::get('/barang', \App\Livewire\MasterBarang::class)->name('barang.index');
        Route::get('/unit-kerja', MasterUnitKerja::class)->name('unit.index');
        Route::get('/personel', MasterPersonel::class)->name('personel.index');
        Route::get('/vendor', MasterVendor::class)->name('vendor.index');
        
        // Activity 3: Registrasi Aset Peralatan
        Route::get('/aset', RegistrasiAset::class)->name('aset.store');

        // Activity 4: Pengadaan / Penerimaan Barang Masuk
        Route::get('/pengadaan/create', CreatePengadaan::class)->name('pengadaan.create');
        Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');

        // Activity 5: Distribusi Barang Keluar
        Route::get('/distribusi/create', CreateDistribusi::class)->name('distribusi.create');
        Route::post('/distribusi', [DistribusiController::class, 'store'])->name('distribusi.store');
        Route::get('/distribusi/{id}/bast', [DistribusiController::class, 'generateBAST'])->name('distribusi.bast');

        // Activity 6: Riwayat Servis (Maintenance)
        Route::get('/servis', KelolaServis::class)->name('servis.index');

        // Activity 7: Stock Opname (Audit)
        Route::get('/opname', StockOpname::class)->name('opname.index');

        // Tambahan: Laporan Per Unit
        Route::get('/laporan-unit', LaporanUnit::class)->name('laporan.unit');
    });

    // Group khusus Pimpinan
    Route::middleware([RolePimpinan::class])->group(function () {
        Route::get('/dashboard/pimpinan', function () {
            return redirect()->route('laporan.dashboard');
        })->name('dashboard.pimpinan');

        // Activity 8: Laporan Monitoring Pimpinan
        Route::get('/laporan/dashboard', DashboardPimpinan::class)->name('laporan.dashboard');

        // Manajemen User
        Route::get('/users', UserManagement::class)->name('users.index');
    });

});
