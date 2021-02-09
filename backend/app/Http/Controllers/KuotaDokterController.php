<?php

namespace App\Http\Controllers;

use App\HistoryKuota;
use App\KuotaDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KuotaDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kuotaDokter = KuotaDokter::with('dokter','kuota')->get();
        $kuotaDokterCount = KuotaDokter::with('dokter','kuota')->count();

        return response([
            'success' => true,
            'message' => 'List Semua Kuota Dokter',
            'count' => $kuotaDokterCount,
            'data' => $kuotaDokter
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
            'id_kuota' => 'required',
            'id_user' => 'required',
            'banyak' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $id_kuota = $request->input('id_kuota');
            $id_user = $request->input('id_user');
            $banyak_kuota = $request->input('banyak');

            $cekKuotaDokter = KuotaDokter::with('dokter','kuota')
            ->where('id_kuota', $id_kuota)
            ->where('id_user', $id_user)
            ->first();

            $id_kuota_dokter = $cekKuotaDokter->id;
            $harga_dasar = $cekKuotaDokter->kuota['harga_dasar'];
            

            if($cekKuotaDokter!=null){
                $harga_total = ($banyak_kuota * $harga_dasar);

                KuotaDokter::where('id_kuota',$id_kuota)
                ->where('id_user', $id_user)
                ->update([
                    'banyak'     => $banyak_kuota,
                ]);
                $historyKuotaUpdate = HistoryKuota::where('id_kuota_dokter', $id_kuota_dokter)
                ->update([
                    'banyak' => $banyak_kuota,
                    'harga' => $harga_total,
                ]);
                
                if ($historyKuotaUpdate) {
                    return response()->json([
                        'success' => true,
                        'message' => 'produk Berhasil Disimpan!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'produk Gagal Disimpan!',
                    ], 400);
                }
            }else{
                if($cekKuotaDokter!=null){
                    $harga_total = ($banyak_kuota * $harga_dasar);

                    KuotaDokter::where('id_kuota',$id_kuota)
                    ->where('id_user', $id_user)
                    ->update([
                        'banyak'     => $banyak_kuota,
                    ]);
                    $historyKuotaUpdate = HistoryKuota::where('id_kuota_dokter', $id_kuota_dokter)
                    ->update([
                        'banyak' => $banyak_kuota,
                        'harga' => $harga_total,
                    ]);
                    
                    if ($historyKuotaUpdate) {
                        return response()->json([
                            'success' => true,
                            'message' => 'produk Berhasil Disimpan!',
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'produk Gagal Disimpan!',
                        ], 400);
                    }
                }else{
                    $kuotaDokter = new KuotaDokter();
                    $kuotaDokter->id_kuota = $request->input('id_kuota');
                    $kuotaDokter->id_user = $request->input('id_user');
                    $kuotaDokter->banyak = $request->input('banyak');
                    $kuotaDokter->save();

                    $historyKuota = new HistoryKuota();
                    $historyKuota->banyak = $request->input('banyak');
                    $historyKuota->harga = $request->input('harga');
                    $kuotaDokter->historyKuota()->save($historyKuota);

                    return response()->json([
                        "message" => "Kuota Dokter record created"
                    ], 201);
                }
                
            }
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
        $kuotaDokter = KuotaDokter::select('id')->where('id', $id)->first();

        if ($kuotaDokter) {
            return response()->json([
                'success' => true,
                'message' => 'Detail kuotaDokter!',
                'data'    => $kuotaDokter
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kuota Dokter Tidak Ditemukan!',
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
        // $tipekuota = DB::table('tipe_kuotas')
        // ->select('*')
        // ->where('deleted_at',null)
        // ->where('id', $id)
        // ->get();

        // return ['data' => $tipekuota];
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
        if (KuotaDokter::where('id', $id)->exists()) {
            $tipekuota = KuotaDokter::find($id);
            $tipekuota->nama = is_null($request->nama) ? $tipekuota->nama : $request->nama;
            $tipekuota->harga = is_null($request->harga) ? $tipekuota->harga : $request->harga;
            $tipekuota->keterangan = is_null($request->keterangan) ? $tipekuota->keterangan : $request->keterangan;
            $tipekuota->save();
    
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
            } else {
            return response()->json([
                "message" => "Kuota not found"
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
        $tipekuota = KuotaDokter::findOrFail($id);
        $tipekuota->delete();

        if ($tipekuota) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Gagal Dihapus!',
            ], 500);
        }
    }
}
