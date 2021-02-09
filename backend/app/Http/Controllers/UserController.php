<?php

namespace App\Http\Controllers;

use App\Alamat;
use Illuminate\Http\Request;
use App\Dokter;
use App\DokterService;
use App\HistoryKuota;
use App\KuotaDokter;
use App\Notif;
use App\Notification;
use App\Profile;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',1)->get();

        $user_count = User::count();

        return response([
            'success' => true,
            'message' => 'List Semua Users',
            'count' => $user_count,
            'data' => $user
        ], 200);
    }
    public function profile($id)
    {
        $user = User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('id', $id)->where('tipe',1)->first();

        $user_count = User::count();

        return response([
            'success' => true,
            'message' => 'List Semua Users',
            'count' => $user_count,
            'data' => $user
        ], 200);
    }
    public function ownerAdmin($id)
    {
        $user = User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',1)->where('id_role',1)->where('pembuat',$id)->get();

        $user_count = User::count();

        return response([
            'success' => true,
            'message' => 'List Semua Users',
            'count' => $user_count,
            'data' => $user
        ], 200);
    }
    public function owner()
    {
        $user = User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',1)->where('id_role',1)->get();

        $user_count = User::count();

        return response([
            'success' => true,
            'message' => 'List Semua Users',
            'count' => $user_count,
            'data' => $user
        ], 200);
    }
    public function daftarNonaktif()
    {
        $user = User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',0)->get();
        $user_count = User::count();
        return response([
            'success' => true,
            'message' => 'List Semua Users',
            'count' => $user_count,
            'data' => $user
        ], 200);
    }

    public function adminAipets(){
        $admin = User::with('role','profile','alamat','dokter')->where('tipe',1)->where('id_role',2)->get();

        return response([
            'success' => true,
            'message' => 'List Semua Admin',
            'data' => $admin
        ], 200);
    }

    public function ownerNewest()
    {   
        $user=User::with('role','profile','alamat','dokter')->where('tipe',1)->where('id_role',1)->orderBy('created_at', 'DESC')->limit(6)->get();  
        $count=User::with('role','profile','alamat','dokter')->where('tipe',1)->where('id_role',1)->orderBy('created_at', 'DESC')->count();  
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $user,
                'count'   => $count,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function ownerNewestAdmin($id)
    {   
        $user=User::with('role','profile','alamat','dokter')->where('tipe',1)->where('id_role',1)->orderBy('created_at', 'DESC')->where('pembuat',$id)->limit(6)->get();  
        $count=User::with('role','profile','alamat','dokter')->where('tipe',1)->where('id_role',1)->orderBy('created_at', 'DESC')->where('pembuat',$id)->count();  
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $user,
                'count'   => $count,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function adminNewest()
    {   
        $admin=User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',1)->where('id_role',2)->orderBy('created_at', 'DESC')->limit(6)->get();  
        $count=User::with('role','profile','alamat','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamat.provinsi','alamat.kota','alamat.kecamatan','alamat.kelurahan')->where('tipe',1)->where('id_role',2)->count();  
        if ($admin) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $admin,
                'count'    => $count,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
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
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);

        }else{

                $user = new User();
                $user->id_role = $request->input('id_role');
                $user->username = $request->input('username');
                $user->tipe = 1;
                $user->pembuat = $request->input('pembuat');
                $user->password = Hash::make($request->input('password'));
                $user->save();

                $profile = new Profile();
                $profile->nama = $request->input('nama');
                $profile->email = $request->input('email');
                $profile->no_telp = $request->input('no_telp');
                $profile->tgl_lahir = $request->input('tgl_lahir');
                $user->profile()->save($profile);

                $alamat = new Alamat();
                $alamat->nama = $request->input('nama_lokasi');
                $alamat->id_provinsi = $request->input('id_provinsi');
                $alamat->id_kabupaten = $request->input('id_kabupaten');
                $alamat->id_kecamatan = $request->input('id_kecamatan');
                $alamat->id_kelurahan = $request->input('id_kelurahan');
                $alamat->pin_point = 134;
                $alamat->alamat = $request->input('alamat');
                $alamat->tipe_alamat = 1;
                $user->alamat()->save($alamat);

                if($request->input('id_role') == 2){

                    $sertif = $request->file('sertifikat');
                    $new_sertif = '/images/'.rand() . '.' . $sertif->getClientOriginalExtension();
                    $sertif->move(public_path('images'), $new_sertif);
                    
                    // insert mass assigment dokter
                    $dokter = new Dokter();  
                    $dokter->sertifikat = $request->input('no_sertifikat');
                    // $dokter->sertifikat = $new_sertif;
                    $dokter->no_ijin = $request->input('no_ijin');
                    $dokter->tanggal_sertifikat = $request->input('tanggal_sertifikat');
                    $dokter->waktu_buka = $request->input('waktu_buka');
                    $dokter->waktu_tutup = $request->input('waktu_tutup');
                    $dokter->tipe = 1;
                    $user->dokter()->save($dokter);
    
                    for ($i=0; $i <count($request->input('service')) ; $i++) {
                        $dokterService = New DokterService();
                        $dokterService->id_service = $request->input('service')[$i];
                        $dokterService->tarif = $request->input('tarif')[$i];
                        $dokterService->tipe = 1;
                        $user->dokterService()->save($dokterService);
                    }
    
                    $notif = new Notif();
                    $notif->id_role = 100;
                    $notif->id_user = 2;
                    $notif->tipe = 1;
                    $notif->data = "admin menambah data user ke table user";
                    $notif->save();
    
                    if ($user) {
                        return response()->json([
                            'success' => true,
                            'message' => 'user Berhasil Disimpan!',
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'user Gagal Disimpan!',
                        ], 400);
                    }
    
                }else{
                    $notif = new Notif();
                    $notif->id_role = 100;
                    $notif->id_user = 1;
                    $notif->tipe = 1;
                    $notif->data = "user menambah data user ke table user";
                    $notif->save();
                }

                if ($user) {
                    return response()->json([
                        'success' => true,
                        'message' => 'user Berhasil Disimpan!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'user Gagal Disimpan!',
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
        $user = User::with('role','profile','alamatSpec','hewan','kuota','dokter','kuotaDokter.kuota','dokterService.service','alamatSpec.provinsi','alamatSpec.kota','alamatSpec.kecamatan','alamatSpec.kelurahan')->where('id', $id)->first();

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
        if (User::where('id', $id)->exists()) {
            if($request->input('password')!=null){
                $user = User::whereId($id)->update([
                    'username' => $request->input('username'),
                    'password' => $request->input('password')
                ]);
            }
            else{
                $user = User::whereId($id)->update([
                    'username' => $request->input('username')
                ]);
            }

            Profile::where('id_user',$id)->update([
                'nama' => $request->input('nama'),
                'email' => $request->input('email'),
                'no_telp' => $request->input('no_telp'),
                'tgl_lahir' => $request->input('tgl_lahir'),
            ]);

            Alamat::where('id_user',$id)->update([
                'nama' => $request->input('nama_lokasi'),
                'alamat' => $request->input('alamat'),
                // 'pin_point' => $request->input('pin_point'),
                'id_provinsi' => $request->input('id_provinsi'),
                'id_kabupaten' => $request->input('id_kabupaten'),
                'id_kecamatan' => $request->input('id_kecamatan'),
                'id_kelurahan' => $request->input('id_kelurahan'),
            ]);

            Dokter::where('id_user',$id)->update([
                'no_ijin' => $request->input('no_ijin'),
                'tanggal_sertifikat' => $request->input('tanggal_sertifikat'),
                'waktu_buka' => $request->input('waktu_buka'),
                'waktu_tutup' => $request->input('waktu_tutup'),
                // 'tipe' => $request->input('tipe'),
            ]);
    

            $dokterService = DokterService::where('id_user',$id);
            $dokterService->delete();

            for ($i=0; $i <count($request->input('service')) ; $i++) {
                $dokterService = New DokterService();
                $dokterService->id_service = $request->input('service')[$i];
                $dokterService->id_user = $id;
                $dokterService->tarif = $request->input('tarif')[$i];
                $dokterService->tipe = 1;
                $dokterService->save();
            }

                if ($user) {
                    return response()->json([
                        'success' => true,
                        'message' => 'user berhasil diupdate!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'user gagal diupdate!',
                    ], 400);
                }
        } else {
            return response()->json([
                "message" => "user not found"
            ], 404);
            
        } 
    }
    public function aktivasi($id)
    {
        $user = User::where('id',$id);
        $user->tipe = 1;
        $user->save();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'user Berhasil Diupdate!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user Gagal Disimpan!',
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
        $user = User::find($id);
        $profile = Profile::where('id_user',$id);
        $alamat = Alamat::where('id_user',$id);
        

        if($user->id_role == 1){
            $user->delete();
            $profile->delete();
            $alamat->delete();
        }elseif ($user->id_role == 2){
            $dokter = Dokter::where('id_user',$id);
            $dokterService = DokterService::where('id_user',$id);
            $kuotaDokter = KuotaDokter::where('id_user',$id)->first();
                $user->delete();
                $profile->delete();
                $alamat->delete();
                $dokter->delete();
                $dokterService->delete();
            if($kuotaDokter!= null){
                $historyKuota = HistoryKuota::where('id_kuota_dokter',$kuotaDokter->id);
                $kuotaDokter->delete();
                $historyKuota->delete();
            }
            
        }elseif ($user->id_role == 3){
            $user->delete();
            $profile->delete();
            $alamat->delete();
        }else{
            return response()->json([
                "message" => "user not found"
            ], 404);
        }

        if ($user) {
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

    public function dokterNonaktif($id){
        $id_auth= auth()->id();
        $dokter = Dokter::find($id);
        $dokter-> delete();
        return redirect('/dokter/view');
    }

    public function admin(){
        $admin = DB::table('users')
        ->select('*')
        ->where('deleted_at',null)
        ->where('role',2)
        ->get();

        return response([
            'success' => true,
            'message' => 'List Semua Kategori',
            'data' => $admin
        ], 200);
    }
}
