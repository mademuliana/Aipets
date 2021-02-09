<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Alamat;
use App\Hewan;
use App\Kouta;
use App\Kuota;
use App\Notif;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class HewanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $hewan = Hewan::with('users')->get();
        $hewan = Hewan::with(
        'tipe',
        'user',
        'alamat',
        'alamat.provinsi',
        'alamat.kota',
        'alamat.kecamatan',
        'alamat.kelurahan',
        'user.profile',
        'admin',
        'admin.profile'
        )
        ->select(
            DB::raw('
            *,
            TIMESTAMPDIFF(YEAR,hewans.tanggal_lahir,CURDATE()) AS umur_tahun,
            TIMESTAMPDIFF(MONTH,hewans.tanggal_lahir,CURDATE()) AS umur_bulan'
            ))
        ->get();
        $hewan_count = Hewan::with('profile','alamat','role','admin')->count();

        return response([
            'success' => true,
            'message' => 'List Semua Hewan',
            'count' => $hewan_count,
            'data' => $hewan
        ], 200);
    }
    public function admin($id)
    {
        $hewan = Hewan::where('pembuat', $id)->get();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function hewanYear($role,$user)
    {   
        if ($role==2) {
            $tahun=DB::table('hewans')
                ->selectRaw('YEAR(created_at) AS year')
                ->where('pembuat',$user)
                ->groupByRaw('year')
                ->get();
        }
        if ($role==3) {
            $tahun=DB::table('hewans')
                ->selectRaw('YEAR(created_at) AS year')
                ->groupByRaw('year')
                ->get();
        }
        

        if ($tahun) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $tahun,

            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
    public function hewanNewest()
    {   
        $hewan=Hewan::with('profile','alamat','role','admin')->orderBy('created_at', 'DESC')->limit(6)->get();  
        $count=Hewan::with('profile','alamat','role','admin')->count();  
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan,
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
    public function hewanNewestAdmin($id)
    {   
        $hewan=Hewan::with('profile','alamat','role','admin')->orderBy('created_at', 'DESC')->where('pembuat',$id)->limit(6)->get();  
        $count=Hewan::with('profile','alamat','role','admin')->where('pembuat',$id)->count();  
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan,
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
    public function hewanCountByDate($id,$date,$role,$user)
    {   
        $now = Carbon::now();
        $tNow=$now->year;
        $mNow=$now->month;
        $hewan=array();
        $month=array();
        $year=array();
        if ($role==3) {
            if ($id==1) {
                $temp=array();
                $hewan[]=['Bulan','Hewan'];
                for($i = 1; $i <= $mNow ; $i++){
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('YEAR(created_at) as Year, COUNT(*) AS count ')
                    ->whereRaw('MONTH(created_at) = '.$i.'&& YEAR(created_at) = '.$tNow)
                    ->groupBy('Year')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=0;
                    }else{
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=$hewanCount->count;
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
            if ($id==2) {
                $temp=array();
                $hewan[]=['Bulan','Hewan'];
                for($i = 1; $i <= 12 ; $i++){
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('COUNT(*) AS count')
                    ->whereRaw('MONTH(created_at) = '.$i.'&& YEAR(created_at)'.$date)
                    ->groupByRaw('MONTH(created_at)')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=0;
                    }else{
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=$hewanCount->count;
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
            if ($id==3) {
                $temp=array();
                $hewan[]=['Tahun','Hewan'];
                $tahun=DB::table('hewans')
                    ->selectRaw('YEAR(created_at) AS year')
                    ->groupByRaw('year')
                    ->get();
                foreach ($tahun as $t) {
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('COUNT(*) AS count')
                    ->whereRaw('YEAR(created_at) ='.$t->year)
                    ->groupByRaw('YEAR(created_at)')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=$t->year;
                        $temp[]=0;
                    }else{
                        $temp[]=$t->year;
                        $temp[]=$hewanCount->count;
                        
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
        }
        else if ($role==2) {
            if ($id==1) {
                $temp=array();
                $hewan[]=['Bulan','Hewan'];
                for($i = 1; $i <= $mNow ; $i++){
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('YEAR(created_at) as Year, COUNT(*) AS count ')
                    ->whereRaw('MONTH(created_at) = '.$i.'&& YEAR(created_at) = '.$tNow)
                    ->where('pembuat',$user)
                    ->groupBy('Year')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=0;
                    }else{
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=$hewanCount->count;
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
            if ($id==2) {
                $temp=array();
                $hewan[]=['Bulan','Hewan'];
                for($i = 1; $i <= 12 ; $i++){
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('COUNT(*) AS count')
                    ->whereRaw('MONTH(created_at) = '.$i.'&& YEAR(created_at)'.$date)
                    ->where('pembuat',$user)
                    ->groupByRaw('MONTH(created_at)')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=0;
                    }else{
                        $temp[]=date("M", mktime(0, 0, 0, $i, 1));
                        $temp[]=$hewanCount->count;
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
            if ($id==3) {
                $temp=array();
                $hewan[]=['Tahun','Hewan'];
                $tahun=DB::table('hewans')
                    ->selectRaw('YEAR(created_at) AS year')
                    ->groupByRaw('year')
                    ->get();
                foreach ($tahun as $t) {
                    $hewanCount=DB::table('hewans')
                    ->selectRaw('COUNT(*) AS count')
                    ->whereRaw('YEAR(created_at) ='.$t->year)
                    ->where('pembuat',$user)
                    ->groupByRaw('YEAR(created_at)')
                    ->first();
                    if ($hewanCount==null) {
                        $temp[]=$t->year;
                        $temp[]=0;
                    }else{
                        $temp[]=$t->year;
                        $temp[]=$hewanCount->count;
                        
                    }
                    $hewan[]=$temp;
                    $temp=null;
                }
            }
        }
        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'year'    => $year,
                'month'    => $month,
                'data'    => $hewan

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
        
        $validator = Validator::make($request->all(),[
            'nama_hewan' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'spesies' => 'required',
            'pemilik' => 'required',
            'pembuat' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ],400);
        }else{
            $hewan = $request->file('gambar_hewan');
            $new_hewan = '/images/'.rand() . '.' . $hewan->getClientOriginalExtension();
            $hewan->move(public_path('images'), $new_hewan);
        
            $hewan = new Hewan();
            $hewan->nama = $request->input('nama_hewan');
            $hewan->microchip = $request->input('microchip');
            $hewan->jenis_kelamin = $request->input('jenis_kelamin');
            $hewan->tanggal_lahir = $request->input('tanggal_lahir');
            $hewan->spesies = $request->input('spesies');
            $hewan->pemilik = $request->input('pemilik');
            $hewan->gambar = $new_hewan;
            $hewan->pembuat = $request->input('pembuat');
            $hewan->save();

            $notif = new Notif();
            $notif->id_role = 101;
            $notif->id_user = $request->input('pembuat');
            $notif->tipe = 1;
            $notif->data = "user menambahkan hewan ke dengan nama "+$request->input('nama_hewan')+" table hewan";
            $notif->save();
                    
            return response()->json([
                "message" => "hewan record created",
                "hewan"=> $request->input('nama_hewan')
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
        // $hewan = Hewan::with('users')->where('id', $id)->get();
        $hewan = Hewan::with(
            'tipe',
            'user',
            'alamat',
            'alamat.provinsi',
            'alamat.kota',
            'alamat.kecamatan',
            'alamat.kelurahan',
            'user.profile',
            'admin',
            'admin.profile'
            )
            ->where('id',$id)
            ->select(
                DB::raw('
                *,
                TIMESTAMPDIFF(YEAR,hewans.tanggal_lahir,CURDATE()) AS umur_tahun,
                TIMESTAMPDIFF(MONTH,hewans.tanggal_lahir,CURDATE()) AS umur_bulan'
                ))
            ->first();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Detail hewan!',
                'data'    => $hewan
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
        $hewan = Hewan::find($id);

        if($request->file('gambar_hewan')!=null){
            $src = $request->file('gambar_hewan');
            $new_hewan = '/images/'.rand() . '.' . $src->getClientOriginalExtension();
            $src->move(public_path('images'), $new_hewan);

            $hewan->nama = $request->input('nama_hewan');
            $hewan->microchip = $request->input('microchip');
            $hewan->jenis_kelamin = $request->input('jenis_kelamin');
            $hewan->tanggal_lahir = $request->input('tanggal_lahir');
            $hewan->spesies = $request->input('spesies');
            $hewan->pemilik = $request->input('pemilik');
            $hewan->gambar = $new_hewan;
            $hewan->pembuat = $request->input('pembuat');
            $hewan->save();

            $notif = new Notif();
            $notif->id_role = 101;
            $notif->id_user = $request->input('pemilik');
            $notif->tipe = 1;
            $notif->data = "user merubah hewan ke table hewan";
            $notif->save();

        }else{
            $hewan->nama = $request->input('nama_hewan');
            $hewan->microchip = $request->input('microchip');
            $hewan->jenis_kelamin = $request->input('jenis_kelamin');
            $hewan->tanggal_lahir = $request->input('tanggal_lahir');
            $hewan->spesies = $request->input('spesies');
            $hewan->pemilik = $request->input('pemilik');
            $hewan->pembuat = $request->input('pembuat');
            $hewan->save();

            $notif = new Notif();
            $notif->id_role = 101;
            $notif->id_user = $request->input('pemilik');
            $notif->tipe = 1;
            $notif->data = "user merubah hewan ke table hewan";
            $notif->save();
        }

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'hewan Berhasil Disimpan!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'hewan Gagal Disimpan!',
            ], 400);
        }
    }

    public function destroy($id)
    {
        $hewan = Hewan::findOrFail($id);
        $hewan->delete();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'Data Hewan Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Hewan Gagal Dihapus!',
            ], 500);
        }
    }

    public function filterByLocation($kota){
        $hewan = Hewan::with(
            'tipe',
            'user',
            'alamat',
            'alamat.provinsi',
            'alamat.kota',
            'alamat.kecamatan',
            'alamat.kelurahan',
            'user.profile',
            'admin',
            'admin.profile'
            )
            ->select(
                DB::raw('
                *,
                TIMESTAMPDIFF(YEAR,hewans.tanggal_lahir,CURDATE()) AS umur_tahun,
                TIMESTAMPDIFF(MONTH,hewans.tanggal_lahir,CURDATE()) AS umur_bulan'
                )
            )->whereHas('alamat', function ($q) use ($kota){
                $q->Where('id_kabupaten', $kota);
            })->get();

        $hewan_count = Hewan::with('alamat')->whereHas('alamat', function ($q) use ($kota){
            $q->Where('id_kabupaten', $kota);
        })->count();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'List Semua Hewan Berdasarkan Lokasi',
                'count' => $hewan_count,
                'data'    => $hewan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }

    public function filterByType($tipe){
        $hewan= Hewan::with(
            'tipe',
            'user',
            'alamat',
            'alamat.provinsi',
            'alamat.kota',
            'alamat.kecamatan',
            'alamat.kelurahan',
            'user.profile',
            'admin',
            'admin.profile'
            )
            ->select(
                DB::raw('
                *,
                TIMESTAMPDIFF(YEAR,hewans.tanggal_lahir,CURDATE()) AS umur_tahun,
                TIMESTAMPDIFF(MONTH,hewans.tanggal_lahir,CURDATE()) AS umur_bulan'
                )
            )->where('id_tipe','LIKE', $tipe)->get();

        $hewan_count= Hewan::with('tipe_hewan')->where('id_tipe','LIKE', $tipe)->count();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'List Semua Hewan Berdasarkan Tipe',
                'count' => $hewan_count,
                'data'    => $hewan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }

    public function filterByTypeLocation($tipe,$kota){
        $hewan = Hewan::with(
            'tipe',
            'user',
            'alamat',
            'alamat.provinsi',
            'alamat.kota',
            'alamat.kecamatan',
            'alamat.kelurahan',
            'user.profile',
            'admin',
            'admin.profile'
            )
            ->select(
                DB::raw('
                *,
                TIMESTAMPDIFF(YEAR,hewans.tanggal_lahir,CURDATE()) AS umur_tahun,
                TIMESTAMPDIFF(MONTH,hewans.tanggal_lahir,CURDATE()) AS umur_bulan'
                )
            )->whereHas('alamat', function ($q) use ($tipe,$kota){
                $q->where('id_tipe','LIKE',$tipe);
                $q->Where('id_kabupaten', $kota);
            })->get();

        $hewan_count = Hewan::with('alamat')->whereHas('alamat', function ($q) use ($tipe,$kota){
            $q->where('id_tipe','LIKE',$tipe);
            $q->Where('id_kabupaten', $kota);
        })->count();

        if ($hewan) {
            return response()->json([
                'success' => true,
                'message' => 'List Semua Hewan Berdasarkan Tipe & Lokasi',
                'count' => $hewan_count,
                'data'    => $hewan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hewan Tidak Ditemukan!',
                'data'    => ''
            ], 404);
        }
    }
}
