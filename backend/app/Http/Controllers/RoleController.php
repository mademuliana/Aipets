<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::get();

        $role_count = Role::count();

        return response([
            'success' => true,
            'message' => 'List Semua Role',
            'count' => $role_count,
            'data' => $role
        ], 200);
    }

    public function notifikasi()
    {
        $role = Role::where('tipe','notifikasi')->get();

        return response([
            'success' => true,
            'message' => 'List Semua Role',
            'data' => $role
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
            'role_name' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);

        }else{
            $role = new Role();
            $role->id = $request->input('id');
            $role->nama = $request->input('role_name');
            $role->save();

            return response()->json([
                "message" => "profile record created"
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
        $role = Role::where('id',$id)->first();

        if ($role) {
            return response()->json([
                'success' => true,
                'message' => 'Detail role!',
                'data'    => $role
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'role Tidak Ditemukan!',
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
        if (Role::where('id', $id)->exists()) {
            $role = Role::find($id);
            $role->id = $request->input('id');
            $role->nama = $request->input('role_name');
            $role->save();
                if ($role) {
                    return response()->json([
                        'success' => true,
                        'message' => 'role berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'role gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "role not found"
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
        $role = Role::findOrFail($id);
        $role->delete();

        if ($role) {
            return response()->json([
                'success' => true,
                'message' => 'role Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'role Gagal Dihapus!',
            ], 500);
        }
    }
}
