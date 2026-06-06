<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDistribusiHeader extends Model
{
    protected $table = 't_distribusi_header';
    protected $primaryKey = 'id_distribusi_h';

    protected $fillable = [
        'id_unit',
        'id_personel_penerima',
        'tgl_distribusi',
    ];

    public function unit()
    {
        return $this->belongsTo(MUnitKerja::class, 'id_unit', 'id_unit');
    }

    public function personelPenerima()
    {
        return $this->belongsTo(MPersonel::class, 'id_personel_penerima', 'id_personel');
    }

    public function details()
    {
        return $this->hasMany(TDistribusiDetail::class, 'id_distribusi_h', 'id_distribusi_h');
    }
}
