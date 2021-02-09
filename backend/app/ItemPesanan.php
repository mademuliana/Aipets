<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $fillable = [
        'id_dokter_service',
        'id_pesanan',
        'banyak'
    ];

    public function pesanan(){
        return $this->belongsTo('App\Pesanan','id_pesanan');
    }

    public function dokter_service(){
        return $this->belongsTo('App\DokterService', 'id_dokter_service');
    }
}
