<?php

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 
//AUTO api prefixed
Route::prefix('account')->group(function () {
    Route::post('login', 'Rest\RestAccountController@login');
    // Route::post('register', 'Rest\RestAccountController@register');

    Route::post('requestid', 'Rest\RestAccountController@requestId')->name('requestid');
   
});
Route:: group(['prefix' => 'musyrifmanagement' , 'middleware'=>['auth:api', 'role:admin_asrama']  ], function () {
    
    Route::post('activate', 'Rest\RestMusyrifManagementController@activate');
     
});
Route:: group(['prefix' => 'masterdata' , 'middleware'=>['auth:api', 'role:admin_asrama,musyrif_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('list', 'Rest\RestMasterDataController@list');
    Route::post('update', 'Rest\RestMasterDataController@update');
    Route::post('delete', 'Rest\RestMasterDataController@delete');
    Route::post('getbyid', 'Rest\RestMasterDataController@getbyid');
    
     
});
Route:: group(['prefix' => 'accountdashboard' , 'middleware'=>'auth:api'  ], function () {
    
    Route::post('logout', 'Rest\RestAccountController@logout');
    Route::post('requestid', 'Rest\RestAccountController@requestId');
    
});
Route:: group(['prefix' => 'dormitorymanagement' , 'middleware'=>['auth:api', 'role:musyrif_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('classes', 'Rest\RestStudentActivityManagementController@classes');
    Route::post('submitpointrecord', 'Rest\RestStudentActivityManagementController@submitpointrecord');
    Route::post('submitmedicalrecord', 'Rest\RestStudentActivityManagementController@submitmedicalrecord');
    Route::post('droppoint', 'Rest\RestStudentActivityManagementController@droppoint');


    // Route::post('activate', 'Rest\RestMusyrifManagementController@activate');
});