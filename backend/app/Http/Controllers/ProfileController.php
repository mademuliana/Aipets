<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = Profile::with('user')->get();
        $profile_count = Profile::with('user')->count();

        return response([
            'success' => true,
            'message' => 'List Semua Profile',
            'count' => $profile_count,
            'data' => $profile
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'no_telp' => 'required',
            'total_tagihan' => 'required',
            'tgl_lahir' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);

        }else{
            $avatar = $request->file('foto_profil');
            $new_avatar = '/images/'.rand() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images'), $new_avatar);

            $profile = new Profile();
            $profile->id_user = $request->input('id_user');
            $profile->nama = $request->input('nama');
            $profile->foto_profil = $new_avatar;
            $profile->email = $request->input('email');
            $profile->no_telp = $request->input('no_telp');
            $profile->tgl_lahir = $request->input('tgl_lahir');
            $profile->save();

            return response()->json([
                "message" => "profile record created"
            ], 201);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::select('id')->where('id', $id)->first(); 


        if ($profile) {
            return response()->json([
                'success' => true,
                'message' => 'detail profile!',
                'data'    => $profile
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'profile tidak ditemukan!',
                'data'    => ''
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $profile = Profile::with('user')->where('id', $id)->get(); 

        // return ['data' => $profile];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $avatar = $request->file('foto_profil');
        $new_avatar = '/images/'.rand() . '.' . $avatar->getClientOriginalExtension();
        $avatar->move(public_path('images'), $new_avatar);

        if($new_avatar!=null){
            $profile = Profile::whereId($id)->update([
                'nama'            => $request->input('nama'),
                'foto_profil'     => $new_avatar,
                'email'           => $request->input('email'),
                'no_telp'         => $request->input('no_telp'),
                'tgl_lahir'       => $request->input('tgl_lahir'),
            ]);
        }else{
            $profile = Profile::whereId($id)->update([
                'nama'            => $request->input('nama'),
                'email'           => $request->input('email'),
                'no_telp'         => $request->input('no_telp'),
                'tgl_lahir'       => $request->input('tgl_lahir'),
            ]);
        }

        if ($profile) {
            return response()->json([
                'success' => true,
                'message' => 'profile Berhasil Disimpan!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'profile Gagal Disimpan!',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        if ($profile) {
            return response()->json([
                'success' => true,
                'message' => 'Profile Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Profile Gagal Dihapus!',
            ], 500);
        }
    }
}
