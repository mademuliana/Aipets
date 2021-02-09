<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\registrationMail;

class RegisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('registration_key','!=','')->orderBy('created_at', 'DESC')->get();
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Detail User!',
                'data'    => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Tidak Ditemukan!',
                'data'    => $status
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
        $random= Str::random(15);
        $user = User::all(); 
        foreach ($user as $item) {
            if ($item->registration_key==$random) {
                $random= Str::random(10);
            }
        }
        
        $user = new User();
        $user->tipe = 0;
        $user->pembuat = $request->input('pembuat');
        $user->email = $request->input('email');
        $user->id_role = 1;
        $user->registration_key = $random;
        $user->save();
        
        $nama = str_replace('@gmail.com', '', $request->input('email'));

        $data = [   
            'subject' => "Link registrasi pemilik hewan",
            'link' => "http://localhost:8080/registration/".$random,
            'nama' => $nama 
        ];
        Mail::to($request->input('email'))->send(new registrationMail($data));
        
        if (Mail::failures()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada yang salah',
            ], 404);
        }
        // $to_name = 'muliana nugraha';
        // $to_email = $request->input('email');
        // $data = array(
        //     `name`=>"Ogbonna Vitalis(sender_name)", 
        //     "body" => "A test mail"
        // );
        // Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
        // $message->to($to_email, $to_name)->subject('Laravel Test Mail');
        // $message->from($to_email,'Test Mail');
        // });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('registration_key', $id)->first();
        $status=false;
        if ($user!=null) {
            $status=true;
        }else{
            $status=false;
        }
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Detail User!',
                'data'    => $status
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Tidak Ditemukan!',
                'data'    => $status
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
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Link Registrasi Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Link Registrasi Gagal Dihapus!',
            ], 500);
        }
    }
    
    public function registration(Request $request)
    {   
        // if (User::where('registration_key', $request->input('key'))->exists()) {
        //     if($request->input('password')!=null){
        //         $user = User::whereId($id)->update([
        //             'username' => $request->input('username'),
        //             'password' => $request->input('password')
        //         ]);
        //     }
        //     else{
        //         $user = User::whereId($id)->update([
        //             'username' => $request->input('username')
        //         ]);
        //     }
        // }
        // $user = new User();
        // $user->id_role = $request->input('id_role');
        // $user->username = $request->input('username');
        // $user->tipe = 1;
        // $user->pembuat = $request->input('pembuat');
        // $user->password = Hash::make($request->input('password'));
        // $user->save();

        // $profile = new Profile();
        // $profile->nama = $request->input('nama');
        // $profile->email = $request->input('email');
        // $profile->no_telp = $request->input('no_telp');
        // $profile->tgl_lahir = $request->input('tgl_lahir');
        // $user->profile()->save($profile);

        // $alamat = new Alamat();
        // $alamat->nama = $request->input('nama_lokasi');
        // $alamat->id_provinsi = $request->input('id_provinsi');
        // $alamat->id_kabupaten = $request->input('id_kabupaten');
        // $alamat->id_kecamatan = $request->input('id_kecamatan');
        // $alamat->id_kelurahan = $request->input('id_kelurahan');
        // $alamat->pin_point = 134;
        // $alamat->alamat = $request->input('alamat');
        // $alamat->tipe_alamat = 1;
        // $user->alamat()->save($alamat);

        // if($request->input('id_role') == 2){

        //     $sertif = $request->file('sertifikat');
        //     $new_sertif = '/images/'.rand() . '.' . $sertif->getClientOriginalExtension();
        //     $sertif->move(public_path('images'), $new_sertif);
            
        //     // insert mass assigment dokter
        //     $dokter = new Dokter();  
        //     $dokter->sertifikat = $request->input('no_sertifikat');
        //     // $dokter->sertifikat = $new_sertif;
        //     $dokter->no_ijin = $request->input('no_ijin');
        //     $dokter->tanggal_sertifikat = $request->input('tanggal_sertifikat');
        //     $dokter->waktu_buka = $request->input('waktu_buka');
        //     $dokter->waktu_tutup = $request->input('waktu_tutup');
        //     $dokter->tipe = 1;
        //     $user->dokter()->save($dokter);

        //     for ($i=0; $i <count($request->input('service')) ; $i++) {
        //         $dokterService = New DokterService();
        //         $dokterService->id_service = $request->input('service')[$i];
        //         $dokterService->tarif = $request->input('tarif')[$i];
        //         $dokterService->tipe = 1;
        //         $user->dokterService()->save($dokterService);
        //     }

        //     $notif = new Notif();
        //     $notif->id_role = 100;
        //     $notif->id_user = 2;
        //     $notif->tipe = 1;
        //     $notif->data = "admin menambah data user ke table user";
        //     $notif->save();

        //     if ($user) {
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'user Berhasil Disimpan!',
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'user Gagal Disimpan!',
        //         ], 400);
        //     }

        // }else{
        //     $notif = new Notif();
        //     $notif->id_role = 100;
        //     $notif->id_user = 1;
        //     $notif->tipe = 1;
        //     $notif->data = "user menambah data user ke table user";
        //     $notif->save();
        // }

        // if ($user) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'user Berhasil Disimpan!',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'user Gagal Disimpan!',
        //     ], 400);
        // }

        return response()->json([
            'success' => true,
            'message' => 'user Berhasil Disimpan!',
        ], 200);
    }
}
