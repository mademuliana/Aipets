<?php

namespace App\Http\Controllers;

use App\Kouta;
use App\Kuota;
use App\Notif;
use App\TipeKouta;
use App\TipeKuota;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kuota = Kuota::all();

        $kuota_count = Kuota::all()->count();

        return response([
            'success' => true,
            'message' => 'List Semua Kuota',
            'count' => $kuota_count,
            'data' => $kuota
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
            'nama_kuota' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $kuota = new Kuota();
            $kuota->nama = $request->input('nama_kuota');
            $kuota->harga_dasar = $request->input('harga_dasar');
            $kuota->tipe = 1;
            $kuota->save();

            $notif = new Notif();
            $notif->id_role = 103;
            $notif->id_user = 3;
            $notif->tipe = 1;
            $notif->data = "superadmin menambahkan kuota dengan nama "+$request->input('nama_kuota')+" ke table kuota";
            $notif->save();

            return response()->json([
                "message" => "Kuota record created"
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
        $kuota = Kuota::where('id', $id)->first();

        if ($kuota) {
            return response()->json([
                'success' => true,
                'message' => 'kuota',
                'data'    => $kuota
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kuota Tidak Ditemukan!',
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
        if (Kuota::where('id', $id)->exists()) {
            $kuota = Kuota::whereId($id)->update([
                'nama' => $request->input('nama_kuota'),
                'harga_dasar' => $request->input('harga_dasar'),
                'tipe' => 1,
            ]);
    
                if ($kuota) {
                    return response()->json([
                        'success' => true,
                        'message' => 'kuota berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'kuota gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "kuota not found"
            ], 404);
            
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
        $kuota = Kuota::findOrFail($id);
        $kuota->delete();

        if ($kuota) {
            return response()->json([
                'success' => true,
                'message' => 'kuota Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kuota Gagal Dihapus!',
            ], 500);
        }
    }
}
