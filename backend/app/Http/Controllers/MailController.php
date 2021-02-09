<?php

namespace App\Http\Controllers;

use App\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mail = Mail::with('pengirim','penerima')->get();

        return response([
            'success' => true,
            'message' => 'List Semua Pesan',
            'data' => $mail
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
            'subject' => 'required',
            'isi_pesan' => 'required',
            'file' => 'required',
            'pengirim' => 'required',
            'penerima' => 'required',
            'status_pesan' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $file = $request->file('file');
            $new_file = '/file/'.rand() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('file'), $new_file);

            $mail = new Mail();
            $mail->subject = $request->input('subject');
            $mail->isi_pesan = $request->input('isi_pesan');
            $mail->file = $new_file;
            $mail->pengirim = $request->input('pengirim');
            $mail->penerima = $request->input('penerima');
            $mail->status_pesan = $request->input('status_pesan');
            $mail->save();

            return response()->json([
                "message" => "Tipe Kuota record created"
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
        $mail = Mail::select('id')->where('id', $id)->first();

        if ($mail) {
            return response()->json([
                'mail'    => $mail
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'mail Tidak Ditemukan!',
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
