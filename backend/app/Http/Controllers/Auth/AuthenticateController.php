<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    public function __invoke(Request $request)
    {
        $user= $request->user();
        $profile= User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id',$user->id)->first();
        return response()->json([
            'user'=>$user,
            'profile'=>$profile->profile,
            'role'=>$profile->role,
        ]);    
    }
}
