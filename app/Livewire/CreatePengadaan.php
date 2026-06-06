<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MVendor;
use App\Models\MBarang;
use App\Models\TPengadaan;
use App\Models\TStokMutasi;
use Illuminate\Support\Facades\DB;

class CreatePengadaan extends Component
{
    public $id_vendor = '';
    public $no_po = '';
    public $tgl_datang = '';
    
    // Array dinamis untuk item barang
    public $items = [];

    protected function rules()
    {
        return [
            'id_vendor' => 'required|exists:m_vendor,id_vendor',
            'no_po' => 'required|string|max:255',
            'tgl_datang' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:m_barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'id_vendor.required' => 'Vendor wajib dipilih.',
        'no_po.required' => 'Nomor PO wajib diisi.',
        'items.*.id_barang.required' => 'Barang wajib dipilih.',
        'items.*.jumlah.min' => 'Jumlah minimal 1.',
    ];

    public function mount()
    {
        // Tambahkan satu baris kosong secara default saat load awal
        $this->addItem();
        $this->tgl_datang = date('Y-m-d');
    }

    public function addItem()
    {
        // Menambahkan elemen array baru
        $this->items[] = ['id_barang' => '', 'jumlah' => 1];
    }

    public function removeItem($index)
    {
        // Menghapus elemen array berdasarkan index
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index array
    }

    public function simpan()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            foreach ($this->items as $item) {
                // 1. Simpan detail pengadaan
                TPengadaan::create([
                    'id_vendor' => $this->id_vendor,
                    'no_po' => $this->no_po,
                    'tgl_datang' => $this->tgl_datang,
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
                    'id_referensi_mutasi' => $this->no_po,
                    'qty_mutasi' => $item['jumlah'],
                    'saldo_stok_akhir' => $saldoBaru,
                ]);
            }

            DB::commit();
            
            // Reset state agar siap untuk transaksi berikutnya
            $this->reset(['id_vendor', 'no_po', 'items']);
            $this->addItem();
            $this->tgl_datang = date('Y-m-d');

            // Flash message
            session()->flash('success', 'Transaksi pengadaan berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan pengadaan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-pengadaan', [
            'vendors' => MVendor::all(),
            'barangs' => MBarang::all(),
        ])->layout('layouts.app');
    }
}
