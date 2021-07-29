<?php

use App\Http\Controllers\Rest\RestAccountController;
use App\Http\Controllers\Rest\RestMasterDataController;
use App\Http\Controllers\Rest\RestMusyrifManagementController;
use App\Http\Controllers\Rest\RestStudentActivityManagementController;
use Illuminate\Http\Request;
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
    Route::post('login', [RestAccountController::class, 'login']);
    // Route::post('register', 'Rest\RestAccountController@register');

    Route::post('requestid', [RestAccountController::class, 'requestId'])->name('requestid');
   
});
Route:: group(['prefix' => 'musyrifmanagement' , 'middleware'=>['auth:api', 'role:admin_asrama']  ], function () {
    
    Route::post('activate', [RestMusyrifManagementController::class, 'activate']);
     
});
Route:: group(['prefix' => 'masterdata' , 'middleware'=>['auth:api', 'role:admin_asrama,musyrif_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('list', [RestMasterDataController::class, 'list']);
    Route::post('update', [RestMasterDataController::class, 'update']);
    Route::post('delete', [RestMasterDataController::class, 'delete']);
    Route::post('getbyid', [RestMasterDataController::class, 'getbyid']);
    
     
});
Route:: group(['prefix' => 'accountdashboard' , 'middleware'=>'auth:api'  ], function () {
    
    Route::post('logout', [RestAccountController::class, 'logout']);
    Route::post('requestid', [RestAccountController::class, 'requestId']);
    
});
Route:: group(['prefix' => 'dormitorymanagement' , 'middleware'=>['auth:api', 'role:musyrif_asrama,admin_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('classes', [RestStudentActivityManagementController::class, 'classes']);
    Route::post('rulecategories', [RestStudentActivityManagementController::class, 'rulecategories']);
    Route::post('submitpointrecord', [RestStudentActivityManagementController::class, 'submitpointrecord']);
    Route::post('submitmedicalrecord', [RestStudentActivityManagementController::class, 'submitmedicalrecord']);
    Route::post('monthlymedicalrecord', [RestStudentActivityManagementController::class, 'monthlymedicalrecord']);
    
    Route::post('droppoint', [RestStudentActivityManagementController::class, 'droppoint']);
    Route::post('followupreminders', [RestStudentActivityManagementController::class, 'followupreminders']);
    Route::post('followup', [RestStudentActivityManagementController::class, 'followup']);
    
    
 
});

//