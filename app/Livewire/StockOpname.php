<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MBarang;
use App\Models\MUnitKerja;
use App\Models\TStockOpname;
use App\Models\TStokMutasi;
use Illuminate\Support\Facades\DB;

class StockOpname extends Component
{
    public $tgl_opname = '';
    public $id_unit = '';
    
    // Array dinamis untuk audit banyak barang sekaligus
    public $items = [];

    public function mount()
    {
        $this->tgl_opname = date('Y-m-d');
        $this->addItem();
    }

    protected $rules = [
        'tgl_opname' => 'required|date',
        'id_unit' => 'required|exists:m_unit_kerja,id_unit',
        'items' => 'required|array|min:1',
        'items.*.id_barang' => 'required|exists:m_barang,id_barang',
        'items.*.qty_fisik' => 'required|integer|min:0',
    ];

    public function addItem()
    {
        $this->items[] = ['id_barang' => '', 'qty_fisik' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function simpan()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            foreach ($this->items as $item) {
                // 1. Ambil qty_sistem (saldo stok mutasi terakhir)
                $stokTerakhir = TStokMutasi::where('id_barang', $item['id_barang'])
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
                $qty_sistem = $stokTerakhir ? $stokTerakhir->saldo_stok_akhir : 0;
                
                // 2. Hitung selisih
                $qty_selisih = $item['qty_fisik'] - $qty_sistem;

                // 3. Simpan data audit opname
                $opname = TStockOpname::create([
                    'id_barang' => $item['id_barang'],
                    'id_unit' => $this->id_unit,
                    'tgl_opname' => $this->tgl_opname,
                    'qty_fisik' => $item['qty_fisik'],
                    'qty_sistem' => $qty_sistem,
                    'qty_selisih' => $qty_selisih,
                ]);

                // 4. Jika ada selisih, buat mutasi "penyesuaian" agar stok sistem sinkron dengan fisik
                if ($qty_selisih != 0) {
                    TStokMutasi::create([
                        'id_barang' => $item['id_barang'],
                        'tgl_mutasi' => $this->tgl_opname,
                        'jenis_mutasi' => 'penyesuaian',
                        'id_referensi_mutasi' => 'OPN-' . $opname->id_opname,
                        'qty_mutasi' => abs($qty_selisih),
                        'saldo_stok_akhir' => $item['qty_fisik'], // Set saldo akhir sesuai hitungan fisik
                    ]);
                }
            }

            DB::commit();
            $this->reset(['id_unit', 'items']);
            $this->addItem();
            $this->tgl_opname = date('Y-m-d');

            session()->flash('success', 'Hasil Stock Opname berhasil disimpan. Stok pada sistem telah disesuaikan dengan perhitungan fisik.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memproses Stock Opname: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $barangs = MBarang::orderBy('nama_barang')->get();
        $units = MUnitKerja::orderBy('nama_unit')->get();
        $opnames = TStockOpname::with(['barang', 'unitKerja'])->orderBy('id_opname', 'desc')->get();
        return view('livewire.stock-opname', compact('barangs', 'units', 'opnames'))->layout('layouts.app');
    }
}
