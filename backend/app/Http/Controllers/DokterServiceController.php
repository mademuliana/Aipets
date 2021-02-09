<?php

namespace App\Http\Controllers;

use App\DokterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DokterServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dokterService = DokterService::with('dokter','profile','service')->get();
        
        $dokterServiceCount = DokterService::with('dokter','profile','service')->count(); 


        return response([
            'success' => true,
            'message' => 'List Semua Dokter Service',
            'count' => $dokterServiceCount,
            'data' => $dokterService
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
            'id_user' => 'required',
            'id_service' => 'required',
            'tarif' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $dokterService = new DokterService();
            $dokterService->id_service = $request->input('id_service');
            $dokterService->id_user = $request->input('id_user');
            $dokterService->tarif = $request->input('tarif');
            $dokterService->tipe = 1;
            $dokterService->save();

            return response()->json([
                "message" => "dokter service record created"
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
        // $dokterService = DokterService::with('dokter','jasa')->where('id',$id)->get(); 
        $dokterService = DokterService::with('service')->where('id_user',$id)->first(); 

        if ($dokterService) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Dokter Service!',
                'data' => $dokterService
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Dokter Service Tidak Ditemukan!',
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
        if (DokterService::where('id', $id)->exists()) {
            $dokterService = DokterService::whereId($id)->update([
                'id_user' => $request->input('id_user'),
                'id_service' => $request->input('id_service'),
                'tarif' => $request->input('tarif'),
                'waktu_buka' => $request->input('waktu_buka'),
                'waktu_tutup' => $request->input('waktu_tutup'),
                'tipe' => $request->input('tipe'),
            ]);
    
                if ($dokterService) {
                    return response()->json([
                        'success' => true,
                        'message' => 'dokter service berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'dokter service gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "Alamat not found"
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
        $ds = DokterService::findOrFail($id);
        $ds->delete();

        if ($ds) {
            return response()->json([
                'success' => true,
                'message' => 'dokter service Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'dokter service Gagal Dihapus!',
            ], 500);
        }
    }
}
