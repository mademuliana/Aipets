<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $fillable = [
        'sertifikat',
        'no_ijin',
        'tanggal_sertifikat',
        'alamat',
        'id_user',
        'tipe'
    ];

    public function users(){
        return $this->belongsTo('App\User','id_user');
    }

    public function kuota(){   
        return $this->belongsToMany('App\Kuota');
    }
}
