<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KuotaDokter extends Model
{
    protected $fillable = [
        'id_kuota',
        'id_user',
        'banyak'
    ];

    public function dokter(){
        return $this->belongsTo('App\User','id_user');
    }

    public function kuota(){
        return $this->belongsTo('App\Kuota','id_kuota');
    }

    public function historyKuota(){   
        return $this->hasMany('App\HistoryKuota','id_kuota_dokter');
    }

    public function profile(){
        return $this->belongsTo('App\Profile','id_user','id_user');
    }
}
