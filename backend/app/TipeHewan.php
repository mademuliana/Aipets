<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipeHewan extends Model
{   
    protected $fillable = [
        'id',
        'nama'
    ];
    
    protected $casts = ['id' => 'string'];

    public function hewan(){
        return $this->hasMany('App\Hewan', 'id_tipe')->select('*', DB::raw('count(*) as total'))->groupBy('*');
    }
}
