<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'nama'
    ];

    public function users(){
        return $this->hasOne('App\User','id');
    }

    public function notification(){
        return $this->hasOne('App\Notif');
    }

    public function pesanan(){
        return $this->hasOne('App\Pesanan');
    }
}
