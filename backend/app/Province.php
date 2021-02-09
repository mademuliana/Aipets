<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name',
    ];

    public function kota(){
        return $this->hasMany('App\Citie','province_id');
    }
}
