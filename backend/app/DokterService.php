<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DokterService extends Model
{
    protected $fillable = [
        'id_service',
        'id_user',
        'tarif',
        'waktu_buka',
        'waktu_tutup',
        'tipe'
    ];

    public function dokter(){
        return $this->belongsTo('App\User','id_user');
    }

    public function service(){
        return $this->hasOne('App\Service','id','id_service');
    }

    public function profile(){
        return $this->belongsTo('App\Profile','id_user');
    }
}
