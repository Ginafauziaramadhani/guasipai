<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TRiwayatServis extends Model
{
    protected $table = 't_riwayat_servis';
    protected $primaryKey = 'id_servis';

    protected $fillable = [
        'id_inventaris',
        'tgl_servis',
        'rincian_kerusakan',
        'biaya_servis',
    ];

    public function inventarisFisik()
    {
        return $this->belongsTo(TInventarisFisik::class, 'id_inventaris', 'id_inventaris');
    }
}
