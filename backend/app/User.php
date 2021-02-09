<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','id_role', 'password','registration_key',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
   

    public function profile(){
        return $this->hasOne('App\Profile','id_user');
    }

    public function alamat(){
        return $this->hasMany('App\Alamat','id_user');
    }
    
    public function alamatSpec(){
        return $this->hasOne('App\Alamat','id_user')->where('alamats.tipe_alamat','=',1);
    }

    public function alamatUtama(){
        return $this->hasMany('App\Alamat','id_user')->where('tipe_alamat',1);
    }

    public function kuota(){
        return $this->hasMany('App\KuotaDokter','id_user');
    }
    public function tipekuota(){
        return $this->hasOneThrough('App\Kuota','App\KuotaDokter','id_user','id','id','id_kuota');
    }

    public function kuotaDokter(){
        return $this->hasMany(KuotaDokter::class, 'id_user');
    }

    public function dokterService(){
        return $this->hasMany(DokterService::class, 'id_user');
    }

    public function mail(){
        return $this->hasOne('App\Mail');
    }

    public function dokter(){
        return $this->hasOne('App\Dokter','id_user');
    }

    public function hewan(){
        return $this->hasMany('App\Hewan','pemilik');
    }

    public function role(){
        return $this->belongsTo('App\Role','id_role');
    }

    public function service(){   
        return $this->belongsToMany('App\Service');
    }


}
