<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hewan extends Model
{
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'spesies',
        'id_tipe',
        'pemilik',
        'pembuat',
        'gambar'
        ];


    public function profile(){
        return $this->hasOneThrough('App\Profile','App\User','id','id','pemilik','id');
    }
    public function role(){
        return $this->hasOneThrough('App\Role','App\User','id','id','pemilik','id');
    }
    public function alamat(){
        return $this->hasOneThrough('App\Alamat','App\User','id','id_user','pemilik','id')->where('alamats.tipe_alamat',1);
    }
    public function admin(){
        return $this->belongsTo('App\User','pembuat');
    }

    public function tipe_hewan(){
        return $this->belongsTo('App\TipeHewan','spesies');
    }
    public function user(){
        return $this->belongsTo('App\User','pemilik');
    }
    public function tipe(){
        return $this->belongsTo('App\TipeHewan','id_tipe');
    }
}
