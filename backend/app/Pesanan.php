<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'id_alamat',
        'waktu_dimulai',
        'waktu_selesai',
        'waktu',
        'total_tagihan',
        'tipe'
    ];

    public function alamat(){
        return $this->belongsTo('App\Alamat','id_alamat');
    }

    public function itemPesanan(){
        return $this->hasMany('App\ItemPesanan', 'id_pesanan');
    }

    public function role(){
        return $this->belongsTo('App\Role');
    }
}
