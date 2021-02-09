<?php

namespace App\Http\Controllers;

use App\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kecamatan = District::all();

        return response([
            'success' => true,
            'message' => 'List Semua Kecamatan',
            'data' => $kecamatan
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
        $kecamatan = District::with('kecamatan')->where('city_id',$id)->get();

        if ($kecamatan) {
            return response()->json([
                'success' => true,
                'message' => 'detail kecamatan!',
                'data'    => $kecamatan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kecamatan tidak ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function spesifik($id)
    {
        $kecamatan = District::with('kecamatan')->where('id',$id)->get();

        if ($kecamatan) {
            return response()->json([
                'success' => true,
                'message' => 'detail kecamatan!',
                'data'    => $kecamatan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kecamatan tidak ditemukan!',
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
