<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $fillable = [
        'alamat_id',
        'id_user',
        'nama',
        'alamat',
        'pin_point',
        'tipe_alamat',
        'id_provinsi',
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan'
    ];
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
    public function provinsi(){
        return $this->belongsTo('App\Province','id_provinsi');
    }

    public function kota(){
        return $this->belongsTo('App\Citie','id_kabupaten');
    }

    public function kecamatan(){
        return $this->belongsTo('App\District','id_kecamatan');
    }

    public function kelurahan(){
        return $this->belongsTo('App\Village','id_kelurahan');
    }


}
