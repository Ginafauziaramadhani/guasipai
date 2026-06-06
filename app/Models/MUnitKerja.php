<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MUnitKerja extends Model
{
    use HasFactory;

    protected $table = 'm_unit_kerja';
    protected $primaryKey = 'id_unit';

    protected $fillable = [
        'nama_unit',
    ];
}
