<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MUnitKerja;
use App\Models\MPersonel;
use App\Models\MBarang;
use App\Models\TDistribusiHeader;
use App\Models\TDistribusiDetail;
use App\Models\TStokMutasi;
use Illuminate\Support\Facades\DB;

class CreateDistribusi extends Component
{
    public $id_unit = '';
    public $id_personel_penerima = '';
    public $tgl_distribusi = '';
    
    // Array dinamis untuk item barang yang akan dikeluarkan
    public $items = [];

    protected function rules()
    {
        return [
            'id_unit' => 'required|exists:m_unit_kerja,id_unit',
            'id_personel_penerima' => 'required|exists:m_personel,id_personel',
            'tgl_distribusi' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:m_barang,id_barang',
            'items.*.qty_keluar' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'id_unit.required' => 'Unit Penerima wajib dipilih.',
        'id_personel_penerima.required' => 'Personel Penerima wajib dipilih.',
        'items.*.id_barang.required' => 'Barang wajib dipilih.',
        'items.*.qty_keluar.min' => 'Jumlah keluar minimal 1.',
    ];

    public function mount()
    {
        $this->addItem();
        $this->tgl_distribusi = date('Y-m-d');
    }

    public function addItem()
    {
        $this->items[] = ['id_barang' => '', 'qty_keluar' => 1];
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
            // 1. Simpan Header
            $header = TDistribusiHeader::create([
                'id_unit' => $this->id_unit,
                'id_personel_penerima' => $this->id_personel_penerima,
                'tgl_distribusi' => $this->tgl_distribusi,
            ]);

            foreach ($this->items as $item) {
                // 2. CEK STOK SEBELUM MENGELUARKAN BARANG
                $stokTerakhir = TStokMutasi::where('id_barang', $item['id_barang'])
                                ->orderBy('id_mutasi', 'desc')
                                ->first();
                
                $saldoSebelumnya = $stokTerakhir ? $stokTerakhir->saldo_stok_akhir : 0;
                
                // Pengecekan ketat
                if ($item['qty_keluar'] > $saldoSebelumnya) {
                    $barang = MBarang::find($item['id_barang']);
                    throw new \Exception("Stok tidak mencukupi untuk barang: " . $barang->nama_barang . ". Sisa stok gudang: " . $saldoSebelumnya);
                }

                $saldoBaru = $saldoSebelumnya - $item['qty_keluar'];

                // 3. Simpan Detail
                TDistribusiDetail::create([
                    'id_distribusi_h' => $header->id_distribusi_h,
                    'id_barang' => $item['id_barang'],
                    'qty_keluar' => $item['qty_keluar'],
                ]);

                // 4. Catat Mutasi Keluar di Buku Besar Stok
                TStokMutasi::create([
                    'id_barang' => $item['id_barang'],
                    'tgl_mutasi' => now(),
                    'jenis_mutasi' => 'keluar',
                    'id_referensi_mutasi' => 'DIST-' . $header->id_distribusi_h,
                    'qty_mutasi' => $item['qty_keluar'],
                    'saldo_stok_akhir' => $saldoBaru,
                ]);
            }

            DB::commit();
            
            // Reset agar form kosong kembali
            $this->reset(['id_unit', 'id_personel_penerima', 'items']);
            $this->addItem();
            $this->tgl_distribusi = date('Y-m-d');

            session()->flash('success', 'Transaksi distribusi barang berhasil disimpan. Stok gudang telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memproses distribusi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-distribusi', [
            'units' => MUnitKerja::all(),
            'personels' => MPersonel::all(),
            'barangs' => MBarang::all(),
        ])->layout('layouts.app');
    }
}
