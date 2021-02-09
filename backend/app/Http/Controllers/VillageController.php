<?php

namespace App\Http\Controllers;

use App\Village;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelurahan = Village::all();

        return response([
            'success' => true,
            'message' => 'List Semua Kelurahan',
            'data' => $kelurahan
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
        $kelurahan = Village::with('kelurahan')->where('district_id',$id)->get();

        if ($kelurahan) {
            return response()->json([
                'success' => true,
                'message' => 'detail kelurahan!',
                'data'    => $kelurahan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kelurahan tidak ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function spesifik($id)
    {
        $kelurahan = Village::with('kelurahan')->where('id',$id)->get();

        if ($kelurahan) {
            return response()->json([
                'success' => true,
                'message' => 'detail kelurahan!',
                'data'    => $kelurahan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kelurahan tidak ditemukan!',
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
