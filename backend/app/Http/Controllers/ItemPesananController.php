<?php

namespace App\Http\Controllers;

use App\ItemPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemPesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemPesanan = ItemPesanan::with('pesanan','dokter_service')->get();


        return response([
            'success' => true,
            'message' => 'List Semua Item Pesanan',
            'data' => $itemPesanan
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
        $items = count( (array) $request->jasa);

        for($item=0; $item < $items; $item++){

            $ds = DB::table('dokter_services')->select('id')
                ->where('id_user',$request->dokter[$item])
                ->where('id_service', $request->jasa[$item])->get();

            $item_pesanan = new ItemPesanan();
            foreach($ds as $id_ds){
                $item_pesanan->id_dokter_service = $id_ds->id;
            }
            $item_pesanan->id_pesanan = $request->id_pesanan;
            $item_pesanan->banyak = $request->banyak[$item];
            $item_pesanan->save();
        }

        return redirect('pesanan/newThree/'.$request->id_pesanan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $itemPesanan = ItemPesanan::select('id')->where('id', $id)->first();

        if ($itemPesanan) {
            return response()->json([
                'itemPesanan'    => $itemPesanan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'itemPesanan Tidak Ditemukan!',
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
        // $item_pesanan = DB::table('item_pesanans')
        // ->select(
        //     '*',
        //     'item_pesanans.id AS id'
        //     )
        // ->where('item_pesanans.id',$id)
        // ->get();

        // return ['data' => $item_pesanan];

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
        $item_pesanan = ItemPesanan::findOrFail($id);
        $item_pesanan->delete();

        if ($item_pesanan) {
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
