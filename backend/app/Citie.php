<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    protected $fillable = [
        'province_id', 'name',
    ];

    public function provinsi(){
        return $this->belongsTo('App\Province','id');
    }
}
