<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = [
        'district_id', 'name',
    ];

    public function kelurahan(){
        return $this->belongsTo('App\District','id');
    }
}
