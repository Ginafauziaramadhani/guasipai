<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPengadaan extends Model
{
    protected $table = 't_pengadaan';
    protected $primaryKey = 'id_pengadaan';

    protected $fillable = [
        'id_vendor',
        'no_po',
        'tgl_datang',
        'jumlah',
        'id_barang',
    ];

    public function vendor()
    {
        return $this->belongsTo(MVendor::class, 'id_vendor', 'id_vendor');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'id_barang', 'id_barang');
    }
}
