<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBarang extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kategori',
        'nama_barang',
        'satuan',
    ];
}
