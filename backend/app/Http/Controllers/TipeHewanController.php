<?php

namespace App\Http\Controllers;

use App\TipeHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipeHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipe_hewan = TipeHewan::all();
        $tipe_hewan_count = TipeHewan::count();

        return response([
            'success' => true,
            'message' => 'List Semua tipe Hewan',
            'count' => $tipe_hewan_count,
            'data' => $tipe_hewan
        ], 200);
    }

    public function hewanCountByType($id,$role)
    {   
        
        $tipe_hewan= TipeHewan::all();
        $temp=array();
        $hewan=array();
        $hewan[]=['Tipe Hewan','Banyak'];
        if ($role==3) {
            foreach ($tipe_hewan as $tipe ) {
                $hewan_count = DB::table('hewans')
                ->selectRaw('hewans.id_tipe, COUNT(*) AS total')
                ->groupBy('hewans.id_tipe')
                ->where('hewans.id_tipe',$tipe->id)
                ->get();
                
                foreach ($hewan_count as $count) {
                    $total=$count->total;
                }
                if ($total!=null) {
                    $temp[]=$tipe->nama;
                    $temp[]=$total;
                    $hewan[]=$temp;
                }
                $temp=null;
                $total=null;
            }
        }
        if ($role==2) {
            foreach ($tipe_hewan as $tipe ) {
                $hewan_count = DB::table('hewans')
                ->selectRaw('hewans.id_tipe, COUNT(*) AS total')
                ->groupBy('hewans.id_tipe')
                ->where('hewans.id_tipe',$tipe->id)
                ->where('hewans.pembuat',$id)
                ->get();
                foreach ($hewan_count as $count) {
                    $total=$count->total;
                }
                if ($total!=null) {
                    $temp[]=$tipe->nama;
                    $temp[]=$total;
                    $hewan[]=$temp;
                }
                $temp=null;
                $total=null;
            }
        }
        
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
        
        
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
            'nama_tipe_hewan' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'tipe_hewan'    => $validator->errors()
            ],400);

        }else{
            $tipe_hewan = new TipeHewan();
            $tipe_hewan->nanama_tipe_hewanma = $request->input('nama_tipe_hewan');
            $tipe_hewan->save();

            return response()->json([
                "message" => "tipe hewan record created"
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
        $tipe_hewan = TipeHewan::where('id','LIKE',$id)->first();

        if ($tipe_hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori!',
                'data'    => $tipe_hewan
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
        //
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
        if (TipeHewan::where('id', $id)->exists()) {
            $tipe_hewan = TipeHewan::find($id);
            $tipe_hewan->nama_tipe_hewan = $request->input('nama_tipe_hewan');
            $tipe_hewan->save();
    
                if ($tipe_hewan) {
                    return response()->json([
                        'success' => true,
                        'message' => 'tipe hewan berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'tipe hewan gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "tipe hewan not found"
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
        $tipe_hewan = TipeHewan::findOrFail($id);
        $tipe_hewan->delete();

        if ($tipe_hewan) {
            return response()->json([
                'success' => true,
                'message' => 'tipe_hewan Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'tipe_hewan Gagal Dihapus!',
            ], 500);
        }
    }
}
