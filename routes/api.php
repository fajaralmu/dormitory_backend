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
    Route::get('requestid', 'Rest\RestAccountController@requestId')->name('requestid');
});
Route:: group(['prefix' => 'musyrifmanagement' , 'middleware'=>['auth:api', 'role:admin_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('userlist', 'Rest\RestMusyrifManagementController@userlist');
    Route::post('activate', 'Rest\RestMusyrifManagementController@activate');
    // Route::post('statistic', 'Rest\RestHistoriesController@statistic');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route:: group(['prefix' => 'accountdashboard' , 'middleware'=>'auth:api'  ], function () {
    // Route::post('user', 'Rest\RestAccountDashboardController@getUser');
    Route::post('logout', 'Rest\RestAccountController@logout');
    // Route::post('updateprofile', 'Rest\RestAccountDashboardController@updateProfile');
    
});
Route:: group(['prefix' => 'dormitorymanagement' , 'middleware'=>['auth:api', 'role:musyrif_asrama']  ], function () {
    //RestMusyrifManagementController
    Route::post('studentlist', 'Rest\RestStudentActivityManagementController@studentlist');
    // Route::post('activate', 'Rest\RestMusyrifManagementController@activate');
});