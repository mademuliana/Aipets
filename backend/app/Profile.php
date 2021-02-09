<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'tgl_lahir'
    ];

    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
}
