<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPersonel extends Model
{
    use HasFactory;

    protected $table = 'm_personel';
    protected $primaryKey = 'id_personel';

    protected $fillable = [
        'nama_personel',
        'jabatan',
    ];
}
