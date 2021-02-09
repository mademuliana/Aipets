<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        $user = new User();
        $user->id_role = $request->input('id_role');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->tipe = 0;
        $user->save();

        $profile = new Profile();
        $profile->nama = $request->input('nama');
        $profile->email = $request->input('email');
        $user->profile()->save($profile);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'user Berhasil Disimpan!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user Gagal Disimpan!',
            ], 400);
        }
    }
}
