<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth',], function () {
    Route::post('signin', 'Auth\SignInController');
    Route::post('signout', 'Auth\SignOutController');

    Route::get('authenticate', 'Auth\AuthenticateController');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('alamats', 'AlamatController');
    Route::resource('dokters', 'DokterController');
    Route::resource('dokterservices', 'DokterServiceController');
    Route::resource('users', 'UserController'); 
    Route::resource('hewans', 'HewanController');
    Route::resource('item_pesanans', 'ItemPesananController');
    Route::resource('kuotas', 'KuotaController');
    Route::resource('pesanans', 'PesananController');
    Route::resource('services', 'ServiceController');
    Route::resource('tipehewans', 'TipeHewanController');
    Route::resource('notifications', 'NotificationController');
    Route::resource('kuotadokters', 'KuotaDokterController');
    Route::resource('profiles', 'ProfileController');
    Route::resource('mails', 'MailController');
    Route::resource('historykuotas', 'HistoryKuotaController');
    Route::resource('roles', 'RoleController');
        
    //admin show
    Route::get('/admin/hewan/{id}', 'HewanController@admin');
    Route::get('/admin/owner/{id}', 'UserController@OwnerAdmin');

    //filter hewan
    Route::get('/hewan/lokasi/{kota}', 'HewanController@filterByLocation');
    Route::get('/hewan/tipe/{tipe}', 'HewanController@filterByType');
    Route::get('/hewan/tipe/lokasi/{tipe}/{kota}', 'HewanController@filterByTypeLocation');

    //filter notifikasi
    Route::get('/notif/filter/{id}/{role}', 'NotificationController@filter');
    Route::get('/rolesNotifikasi', 'RoleController@notifikasi');

    /// Home Page API
    
    //Graph Superadmin
    Route::get('/hewan/year/{role}/{user}', 'HewanController@hewanYear');
    Route::get('/hewan/type/{id}/{role}', 'TipeHewanController@hewanCountByType'); 
    Route::get('/hewan/{id}/{date}/{role}/{user}', 'HewanController@hewanCountByDate');

    // List Superadmin    
    Route::get('/newestHewan', 'HewanController@hewanNewest');
    Route::get('/newestOwner', 'UserController@ownerNewest');
    Route::get('/newestAdmin', 'UserController@adminNewest');
    Route::get('/newestKuota', 'HistoryKuotaController@kuotaNewest');

    // List Admin
    Route::get('/newestHewan/{id}', 'HewanController@hewanNewestAdmin');
    Route::get('/newestOwner/{id}', 'UserController@ownerNewestAdmin');
    Route::get('/newestKuota/{id}', 'HistoryKuotaController@kuotaNewestAdmin');
    
    //profile
    Route::get('/profile/{id}', 'UserController@profile');

    //card
    Route::get('/soldKuota', 'HistoryKuotaController@kuotaSold');

    
});

    ///Daerah
    //Main
    Route::resource('provinsi', 'ProvinceController');
    Route::resource('kota', 'CityController');
    Route::resource('kecamatan', 'DistrictController');
    Route::resource('kelurahan', 'VillageController');
    //khusus
    Route::get('kota/spesifik/{id}', 'CityController@spesifik');
    Route::get('kecamatan/spesifik/{id}', 'DistrictController@spesifik');
    Route::get('kelurahan/spesifik/{id}', 'VillageController@spesifik');

    Route::get('/adminAipets', 'UserController@adminAipets');
    Route::put('/aktivasi/{id}', 'UserController@aktivasi');
    Route::get('/aktivasi', 'UserController@daftarNonaktif');
    Route::get('/owner', 'UserController@owner');
    
    ///Regis
    Route::resource('/regis', 'RegisController');
    
    //email
    Route::post('/registration', 'RegisController@registration');
    
 
