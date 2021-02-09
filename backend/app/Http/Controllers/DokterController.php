<?php

namespace App\Http\Controllers;

use App\Dokter;
use App\Notif;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = User::with('role','profile','alamat','dokter','kuotaDokter.kuota','dokterService.service',
        'alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id_role',2)->get();

        $admin_count = User::with('role','profile','alamat','dokter','kuotaDokter.kuota','dokterService.service',
        'alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id_role',2)->count();
        
        return response([
            'success' => true,
            'message' => 'List Semua Dokter',
            'count' => $admin_count,
            'data' => $admin
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
        $validator = Validator::make($request->all(),[
            'nama_dokter' => 'required',
            'profil' => 'required',
            'sertifikat' => 'required',
            'no_ijin' => 'required',
            'tanggal_sertifikat' => 'required',
            'id_user' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $sertif = $request->file('sertifikat');
            $new_sertif = '/images/'.rand() . '.' . $sertif->getClientOriginalExtension();
            $sertif->move(public_path('images'), $new_sertif);
            
            // insert mass assigment dokter
            $dokter = new Dokter();
            $dokter->sertifikat = $new_sertif;
            $dokter->no_ijin = $request->input('no_ijin');
            $dokter->tanggal_sertifikat = $request->input('tanggal_sertifikat');
            $dokter->id_user = $request->input('id_user');
            $dokter->save();

            $notif = new Notif();
            $notif->id_role = 102;
            $notif->id_user = $request->input('id_user');
            $notif->tipe = 1;
            $notif->data = "dokter menambah data user ke table dokter";
            $notif->save();

            return response()->json([
                "message" => "Dokter record created"
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
        // $dokter = Dokter::with('users')->where('id', $id)->get();
        $admin = User::with('role','profile','alamat','dokter','kuotaDokter.kuota','dokterService.service',
        'alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id_role',2)->where('id', $id)->first();

        $admin_count = User::with('role','profile','alamat','dokter','kuotaDokter.kuota','dokterService.service',
        'alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id_role',2)->where('id', $id)->count();
        
        return response([
            'success' => true,
            'message' => 'List Semua Dokter',
            'count' => $admin_count,
            'data' => $admin
        ], 200);
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     
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
        $sertif = $request->file('sertifikat');
        $new_sertif = '/images/'.rand() . '.' . $sertif->getClientOriginalExtension();
        $sertif->move(public_path('images'), $new_sertif);

        if($new_sertif != null){
            $dokter = Dokter::where('id', $id)->update([
                'sertifikat' => $new_sertif,
                'no_ijin' => $request->no_ijin,
                'tanggal_sertifikat' => $request->tanggal_sertifikat,
                'alamat' => $request->alamat,
                'id_user' => $request->id_admin,
            ]);
        }else{
            $dokter = Dokter::where('id', $id)->update([
                'nama_dokter' => $request->nama_dokter,
                'no_ijin' => $request->no_ijin,
                'tanggal_sertifikat' => $request->tanggal_sertifikat,
                'alamat' => $request->alamat,
                'id_user' => $request->id_admin,
            ]);
        }

        if ($dokter) {
            return response()->json([
                'success' => true,
                'message' => 'dokter Berhasil Disimpan!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'dokter Gagal Disimpan!',
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
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();

        if ($dokter) {
            return response()->json([
                'success' => true,
                'message' => 'Data Dokter Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Dokter Gagal Dihapus!',
            ], 500);
        }
    }
}
