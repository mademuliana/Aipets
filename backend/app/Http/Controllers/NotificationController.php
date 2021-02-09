<?php

namespace App\Http\Controllers;

use App\Notif;
use App\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Notif::all();
        $dataCount = Notif::count();

        return response([
            'success' => true,
            'message' => 'List Semua Notifikasi',
            'count' => $dataCount,
            'data' => $data
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Notif::with('role','profile','user.role')->where('id_user',$id)->where('read_at',null)->get();
        $dataCount = Notif::where('id_user',$id)->where('read_at',null)->count();

        return response([
            'success' => true,
            'message' => 'List Semua Notifikasi',
            'count' => $dataCount,
            'data' => $data
        ], 200);
    }

    public function filter($id,$role)
    {
        $data = Notif::with('role','profile','user.role')->where('id_user',$id)->where('read_at',null)->where('id_role',$role)->get();

        return response([
            'success' => true,
            'message' => 'List Semua Notifikasi',
            'data' => $data
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        if (Notif::where('id', $id)->exists()) {
            $notif = Notif::find($id);
            $notif->read_at = Carbon::now();
            $notif->save();
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
        $notif = Notif::findOrFail($id);
        $notif->delete();

        if ($notif) {
            return response()->json([
                'success' => true,
                'message' => 'notifikasi Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'notifikasi Gagal Dihapus!',
            ], 500);
        }
    }

    // menampilkan pesan yang ada pada superadmin
    public function filterNotifByHewan($hewan){
        $notif = Notif::with('role')->whereHas('role', function($q) use ($hewan){
            $q->where('id_role',$hewan);
        });

        if ($notif) {
            return response()->json([
                'success' => true,
                'message' => 'notifikasi',
                'data' => $notif
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'notifikasi Gagal Dihapus!',
            ], 500);
        }
    }
    
    // menampilkan pesan yang ada pad admin
    public function AdminHistory(){
        // return view('table/Notif',[
        //     'nama_lengkap' => username_function(),
        //     'role'=> role_function()
        //     ]);
    }
}
