<?php

namespace App\Http\Controllers;
use App\Alamat;
use App\User;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $alamat = Alamat::with('user','user.profile','user.role','provinsi','kota','kecamatan','kelurahan')->get();

        $alamat_count = Alamat::with('user','user.profile','user.role','provinsi','kota','kecamatan','kelurahan')->count();


        return response([
            'success' => true,
            'message' => 'List Semua Alamat',
            'count' => $alamat_count,
            'data' => $alamat
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
            'nama' => 'required',
            'user' => 'required',
            'id_provinsi' => 'required',
            'id_kabupaten' => 'required',
            'id_kecamatan' => 'required',
            'id_kelurahan' => 'required',
            'alamat' => 'required',
            'tipe' => 'required',
        ]);

        if($request->input('tipe')==1){
            $alamatCheck = Alamat::where('id_user', $request->input('user'))->where('tipe_alamat',1)->first();
            if($alamatCheck!=null){
                $alamatCheck->tipe_alamat = 2;
                $alamatCheck->save();
            }
        }
        
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $alamat = new Alamat();
            $alamat->nama = $request->input('nama');
            $alamat->id_user = $request->input('user');
            $alamat->id_provinsi = $request->input('id_provinsi');
            $alamat->id_kabupaten = $request->input('id_kabupaten');
            $alamat->id_kecamatan = $request->input('id_kecamatan');
            $alamat->id_kelurahan = $request->input('id_kelurahan');
            $alamat->pin_point = 1;
            $alamat->alamat = $request->input('alamat');
            $alamat->tipe_alamat = $request->input('tipe');
            $alamat->save();

            return response()->json([
                "message" => "alamat record created"
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
        $alamat = Alamat::where('id_user', $id)->where('tipe_alamat',1)->first();

        if ($alamat) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Alamat!',
                'data'    => $alamat
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
        if (Alamat::where('id', $id)->exists()) {
            $alamat = Alamat::find($id);
            $alamat->nama = $request->input('nama');
            $alamat->id_user = $request->input('user');
            $alamat->id_provinsi = $request->input('id_provinsi');
            $alamat->id_kabupaten = $request->input('id_kabupaten');
            $alamat->id_kecamatan = $request->input('id_kecamatan');
            $alamat->id_kelurahan = $request->input('id_kelurahan');
            $alamat->pin_point = 1;
            $alamat->alamat = $request->input('alamat');
            $alamat->tipe_alamat = $request->input('tipe');
            $alamat->save();
    
                if ($alamat) {
                    return response()->json([
                        'success' => true,
                        'message' => 'alamat berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'alamat gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "Alamat not found"
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
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();

        if ($alamat) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Alamat Gagal Dihapus!',
            ], 500);
        }
    }

    public function MainAddr($id){

        $idauth = auth()->id();

        Alamat::where('id_user', $idauth)->update([
            'tipe_alamat' => 0,
        ]);

        Alamat::where('id', $id)->update([
            'tipe_alamat' => 1,
        ]);

        return redirect('profile/'.$idauth);
    }
}
