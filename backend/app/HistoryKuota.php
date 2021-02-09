<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryKuota extends Model
{
    protected $fillable = [
        'id_kuota_dokter',
        'banyak',
        'tipe'
    ];

    public function kuota_dokter(){   
        return $this->belongsTo('App\KuotaDokter','id_kuota_dokter');
    }
}
