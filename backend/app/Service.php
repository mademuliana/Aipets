<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'nama_service',
        'tipe',
        'harga_dasar'
    ];

    public function service(){
        return $this->belongsTo('App\Service','id');
    }


}
