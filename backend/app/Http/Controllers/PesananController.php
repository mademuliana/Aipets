<?php

namespace App\Http\Controllers;

use App\DokterService;
use App\ItemPesanan;
use App\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pesanan = Pesanan::with('alamat')->get();
        $pesanan_count = Pesanan::with('alamat')->count();

        return response([
            'success' => true,
            'message' => 'List Semua Pesanan',
            'count' => $pesanan_count,
            'data' => $pesanan
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
            'id_alamat' => 'required',
            'waktu_dimulai' => 'required',
            'waktu_selesai' => 'required',
            'durasi' => 'required',
            'total_tagihan' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);

        } else {
            $pesanan = New Pesanan();
            $pesanan->id_alamat = $request->input('id_alamat');
            $pesanan->waktu_dimulai = $request->input('waktu_dimulai');
            $pesanan->waktu_selesai = $request->input('waktu_selesai');
            $pesanan->durasi = $request->input('durasi');
            $pesanan->total_tagihan = $request->input('total_tagihan');
            $pesanan->status = $request->input('status');
            $pesanan->save();

            //jasa
            for ($i=0; $i <count($request->input('item_pesanan')) ; $i++) {

                $dokterService = DokterService::with('dokter','jasa')
                ->select('id')
                ->where('id_user',$request->input('id_user')[$i])
                ->where('id_service', $request->input('id_service')[$i])
                ->get(); 


                $itemPesanan = New ItemPesanan();
                foreach($dokterService as $id_ds){
                    $itemPesanan->id_dokter_service = $id_ds->id;
                }
                $itemPesanan->banyak = $request->input('banyak')[$i];
                $pesanan->itemPesanan()->save($itemPesanan);
            }

            $i=0;
            if ($pesanan) {
                return response()->json([
                    'success' => true,
                    'message' => 'pesanan Berhasil Disimpan!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'pesanan Gagal Disimpan!',
                ], 400);
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
        $pesanan = Pesanan::select('id')->where('id',$id)->first();

        if ($pesanan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail pesanan!',
                'data'    => $pesanan
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
        $pesanan = Pesanan::whereId($id)->update([
            'id_alamat'          => $request->input('id_alamat'),
            'waktu_dimulai'      => $request->input('waktu_dimulai'),
            'waktu_diantar'      => $request->input('waktu_diantar'),
            'waktu_selesai'      => $request->input('waktu_selesai'),
            'durasi'             => $request->input('durasi'),
            'total_tagihan'      => $request->input('total_tagihan'),
        ]);
        
        $itemPesanan = itemPesanan::where('id_pesanan',$id);
        $itemPesanan->delete();
        
        //jasa
        for ($i=0; $i <count($request->input('item_pesanan')) ; $i++) {

            $dokterService = DokterService::with('dokter','jasa')
            ->select('id')
            ->where('id_user',$request->input('id_user')[$i])
            ->where('id_service', $request->input('id_service')[$i])
            ->get(); 


            $item_pesanan = New ItemPesanan();
            foreach($dokterService as $id_ds){
                $item_pesanan->id_dokter_service = $id_ds->id;
            }
            $item_pesanan->banyak = $request->input('banyak')[$i];
            $pesanan->itemPesanan()->save($item_pesanan);
        }

        if ($item_pesanan) {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $itemPesanan = ItemPesanan::where('id_pesanan',$id);
        $pesanan->delete();
        $itemPesanan->delete();

        if ($pesanan) {
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


    public function pesananUpdated(Request $request, $id){
        $awal  = $request->waktu_dimulai;
        $akhir = $request->waktu_selesai;
        // $waktu = $akhir - $awal;

        Pesanan::where('id', $id)->update([
            'id_alamat' => $request->id_alamat,
            'waktu_dimulai' => $request->waktu_dimulai,
            'waktu_selesai' => $request->waktu_selesai,
            'waktu' => 10,
            'total_tagihan' => 10,
            'status' => 0
        ]);

        $items = count( (array) $request->jasa);

        for($item=0; $item < $items; $item++){

            $ds = DB::table('dokter_services')->select('id')
                ->where('id_dokter',$request->dokter[$item])
                ->where('id_service', $request->jasa[$item])->get();
            
            foreach($ds as $id_ds){
                ItemPesanan::where('id', $request->id[$item])->update([
                    'id_dokter_service' => $id_ds->id,
                    'banyak' => $request->banyak[$item]
                ]);
            }
        }

        return redirect('pesanan/updateVerification/'.$id);

    }

    public function postPesananNeWForm(Request $request){

        $validasi = $request->validate([
            'id_alamat' => 'required'
        ]);

        $pesanan = new Pesanan();
        $pesanan->id_alamat = $request->id_alamat;
        $pesanan->waktu_dimulai = null;
        $pesanan->waktu_selesai = null;
        $pesanan->waktu = null;
        $pesanan->total_tagihan = null;
        $pesanan->status = "1";
        $pesanan->fill($validasi);

        $pesanan->save();

        $last = Pesanan::max('id');        
        $pesanan = Pesanan::where('id',$last)->get();
        foreach($pesanan as $idPesanan){
            $id_pesanan = $idPesanan->id;
        }

        return redirect('pesanan/newTwo/'.$id_pesanan);
        // return redirect('pesanan/newTwo');

    }

    public function pesananNeWFormThree(Request $request, $id){
        //pesanan
        $pesanan = DB::table('pesanans')
        ->select(
            '*',
            'pesanans.id AS id'
            )
        ->leftjoin('alamats','alamats.id','=','pesanans.id_alamat')
        ->where('pesanans.deleted_at',null)
        ->where('alamats.deleted_at',null)
        ->where('pesanans.id', $id)
        ->get();

        //item pesanan
        $item_pesanan = DB::table('item_pesanans')
        ->select(
            '*',
            'item_pesanans.id AS id'
            )
        ->leftjoin('pesanans','pesanans.id','=','item_pesanans.id_pesanan')
        ->leftjoin('dokter_services','dokter_services.id','=','item_pesanans.id_dokter_service')
        ->leftjoin('services','services.id','=','dokter_services.id_service')
        ->leftjoin('dokters','dokters.id','=','dokter_services.id_dokter')
        ->where('item_pesanans.deleted_at',null)
        ->where('dokters.deleted_at',null)
        ->where('dokter_services.deleted_at',null)
        ->where('services.deleted_at',null)
        ->where('pesanans.id', $id)
        ->get();

        
        // dd($pesanan);
        return view('form/pesananNewFour',compact('pesanan'),['itempesanan' => $item_pesanan]);

    }

    public function updateVerification(Request $request, $id){
        //pesanan
        $pesanan = DB::table('pesanans')
        ->select(
            '*',
            'pesanans.id AS id'
            )
        ->leftjoin('alamats','alamats.id','=','pesanans.id_alamat')
        ->where('pesanans.deleted_at',null)
        ->where('alamats.deleted_at',null)
        ->where('pesanans.id', $id)
        ->get();

        //item pesanan
        $item_pesanan = DB::table('item_pesanans')
        ->select(
            '*',
            'item_pesanans.id AS id'
            )
        ->leftjoin('pesanans','pesanans.id','=','item_pesanans.id_pesanan')
        ->leftjoin('dokter_services','dokter_services.id','=','item_pesanans.id_dokter_service')
        ->leftjoin('services','services.id','=','dokter_services.id_service')
        ->leftjoin('dokters','dokters.id','=','dokter_services.id_dokter')
        ->where('item_pesanans.deleted_at',null)
        ->where('dokters.deleted_at',null)
        ->where('dokter_services.deleted_at',null)
        ->where('services.deleted_at',null)
        ->where('pesanans.id', $id)
        ->get();

        
        // dd($pesanan);
        return view('form/pesananEditVerification',compact('pesanan'),['itempesanan' => $item_pesanan]);

    }

    public function postPesananNeWFormThree(Request $request){

        $pesanan = $request->session()->get('pesanans');
        $pesanan->save();

        $request->session()->forget('pesanans');

        return redirect()->route('pesanan.index');
        // return redirect('table/pesanan');

    }

    public function pesananUpdate(Request $request, $id){
        Pesanan::where('id', $id)->update([
            'waktu_dimulai' => $request->waktu_dimulai,
            'waktu_selesai' => $request->waktu_selesai,
            'waktu' => 10,
            'status' => 0,
            'total_tagihan' => $request->total_tagihan,
        ]);

        return redirect('/pesanan');
    }

    public function editVerification(Request $request, $id){
        Pesanan::where('id', $id)->update([
            'total_tagihan' => $request->total_tagihan,
        ]);

        return redirect('/pesanan');
    }
}
