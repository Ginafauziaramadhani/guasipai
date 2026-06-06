<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TInventarisFisik extends Model
{
    protected $table = 't_inventaris_fisik';
    protected $primaryKey = 'id_inventaris';

    protected $fillable = [
        'id_barang',
        'serial_number',
        'id_unit_sekarang',
        'status_kondisi',
    ];

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'id_barang', 'id_barang');
    }

    public function unitKerja()
    {
        return $this->belongsTo(MUnitKerja::class, 'id_unit_sekarang', 'id_unit');
    }
}
