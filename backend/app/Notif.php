<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    protected $fillable = [
        'id_role',
        'id_user',
        'tipe',
        'data'
    ];

    public function role(){
        return $this->belongsTo('App\Role','id_role');
    }
    public function profile(){
        return $this->belongsTo('App\Profile','id_user','id_user');
    }
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
}
