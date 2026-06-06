<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDistribusiDetail extends Model
{
    protected $table = 't_distribusi_detail';
    protected $primaryKey = 'id_distribusi_d';

    protected $fillable = [
        'id_distribusi_h',
        'id_barang',
        'qty_keluar',
    ];

    public function header()
    {
        return $this->belongsTo(TDistribusiHeader::class, 'id_distribusi_h', 'id_distribusi_h');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'id_barang', 'id_barang');
    }
}
