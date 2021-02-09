<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DokterService;
use App\Notif;
use App\Notification;
use App\Notifications\DefaultNotification;
use App\Service;
use App\User;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Service = Service::all();

        $service_count = Service::count();

        return response([
            'success' => true,
            'message' => 'List Semua Service',
            'count' => $service_count,
            'data' => $Service
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
            'nama_service' => 'required',
            'harga_dasar' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);

        }else{
            $service = new Service();
            $service->nama = $request->input('nama_service');
            $service->tipe = 1;
            $service->harga_dasar = $request->input('harga_dasar');
            $service->save();

            $notif = new Notif();
            $notif->id_role = 104;
            $notif->id_user = 3;
            $notif->tipe = 1;
            $notif->data = "superadmin menambahkan data service dengan nama "+$request->input('nama_service')+" ke table service";
            $notif->save();

            return response()->json([
                "message" => "student record created"
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
        $Service = Service::where('id',$id)->first();

        if ($Service) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Service!',
                'data'    => $Service
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Tidak Ditemukan!',
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
        // $categories = Service::findOrFail($id);
        // return ['data' => $categories];
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
        if (Service::where('id', $id)->exists()) {
            $service = Service::find($id);
            $service->nama = $request->input('nama_service');
            $service->tipe = 1;
            $service->harga_dasar = $request->input('harga_dasar');
            $service->save();
    
                if ($service) {
                    return response()->json([
                        'success' => true,
                        'message' => 'service berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'service gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "service not found"
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
        $Service = Service::findOrFail($id);
        $Service->delete();

        if ($Service) {
            return response()->json([
                'success' => true,
                'message' => 'service Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'service Gagal Dihapus!',
            ], 500);
        }
    }

    public function dokterServiceDelete($id){
        $dokterService = DokterService::find($id);
        $dokterService-> delete();
        return redirect('/dokterservice');
    }
}
