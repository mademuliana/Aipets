<?php

namespace App\Http\Controllers;

use App\Citie;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kota = Citie::all();

        return response([
            'success' => true,
            'message' => 'List Semua Kota',
            'data' => $kota
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
        $kota = Citie::with('provinsi')->where('province_id',$id)->get();

        if ($kota) {
            return response()->json([
                'success' => true,
                'message' => 'detail kota!',
                'data'    => $kota
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kota tidak ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function spesifik($id)
    {
        $kota = Citie::with('provinsi')->where('id',$id)->first();

        if ($kota) {
            return response()->json([
                'success' => true,
                'message' => 'detail kota!',
                'data'    => $kota
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kota tidak ditemukan!',
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
