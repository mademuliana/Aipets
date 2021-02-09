<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kuota extends Model
{
    protected $fillable = [
        'nama',
        'tipe',
        'harga_dasar'
    ];

    public function kuotaDokter(){
        return $this->belongsToMany(KuotaDokter::class)
                        ->withPivot([
                            'banyak',
                        ]);
    }
}
