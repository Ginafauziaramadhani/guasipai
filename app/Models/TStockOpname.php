<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TStockOpname extends Model
{
    protected $table = 't_stock_opname';
    protected $primaryKey = 'id_opname';

    protected $fillable = [
        'id_barang',
        'id_unit',
        'tgl_opname',
        'qty_fisik',
        'qty_sistem',
        'qty_selisih',
    ];

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'id_barang', 'id_barang');
    }

    public function unitKerja()
    {
        return $this->belongsTo(MUnitKerja::class, 'id_unit', 'id_unit');
    }
}
