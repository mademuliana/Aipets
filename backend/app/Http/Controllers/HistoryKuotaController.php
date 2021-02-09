<?php

namespace App\Http\Controllers;

use App\HistoryKuota;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryKuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historyKuota = HistoryKuota::with('kuota_dokter','kuota_dokter.profile','kuota_dokter.kuota')->get();

        $historyKuotaCount = HistoryKuota::with('kuota_dokter','kuota_dokter.profile','kuota_dokter.kuota')->count();

        return response([
            'success' => true,
            'message' => 'List Semua History Kuota',
            'count' => $historyKuotaCount,
            'data' => $historyKuota
        ], 200);
    }
    public function kuotaNewest()
    {   
        $hewan=HistoryKuota::with('kuota_dokter','kuota_dokter.profile','kuota_dokter.kuota')->orderBy('created_at', 'DESC')->limit(6)->get();  
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function kuotaSold()
    {   
        $kuota=DB::table('history_kuotas')
        ->selectRaw('SUM(banyak) AS total')
        ->first(); 
        if ($kuota) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $kuota,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function kuotaNewestAdmin($id)
    {   
        $hewan=HistoryKuota::leftJoin('kuota_dokters', function($join) {
            $join->on('history_kuotas.id_kuota_dokter', '=', 'kuota_dokters.id');
          })->with('kuota_dokter.profile','kuota_dokter.kuota')->select('*','history_kuotas.id as id','history_kuotas.created_at as created_at','history_kuotas.deleted_at as deleted_at','history_kuotas.updated_at as updated_at','history_kuotas.banyak as banyak')->orderBy('history_kuotas.created_at', 'DESC')->limit(6)->get();  
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan,

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
        $validator = Validator::make($request->all(),[
            'id_kuota_dokter' => 'required',
            'banyak' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $historyKuota = new HistoryKuota();
            $historyKuota->id_kuota_dokter = $request->input('id_kuota_dokter');
            $historyKuota->banyak = $request->input('id_kuota_dokter');
            $historyKuota->save();

            return response()->json([
                "message" => "history kuota record created"
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
        $historyKuota = HistoryKuota::with('kuota_dokter','kuota_dokter.profile','kuota_dokter.kuota')->where('id', $id)->first();

        if ($historyKuota) {
            return response()->json([
                'success' => true,
                'message' => 'Detail historyKuota',
                'data'    => $historyKuota
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'historyKuota Tidak Ditemukan!',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
