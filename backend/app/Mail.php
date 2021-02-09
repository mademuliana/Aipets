<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = [
        'subject',
        'isi_pesan',
        'file',
        'pengirim',
        'penerima',
        'status_pesan'
    ];

    public function pengirim(){
        return $this->belongsTo('App\User','pengirim');
    }

    public function penerima(){
        return $this->belongsTo('App\User','penerima');
    }
}
