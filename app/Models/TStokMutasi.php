<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TStokMutasi extends Model
{
    protected $table = 't_stok_mutasi';
    protected $primaryKey = 'id_mutasi';

    protected $fillable = [
        'id_barang',
        'tgl_mutasi',
        'jenis_mutasi',
        'id_referensi_mutasi',
        'qty_mutasi',
        'saldo_stok_akhir',
    ];

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'id_barang', 'id_barang');
    }
}
