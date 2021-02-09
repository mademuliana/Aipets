<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'city_id', 'name',
    ];

    public function kecamatan(){
        return $this->belongsTo('App\Citie','id');
    }
}
