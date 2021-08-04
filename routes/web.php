<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\RaporController;
use Illuminate\Support\Facades\Route;

Route::get('/login', 'JoinController@create')->name('login');
Route::get('/callback', 'JoinController@store');

Route::post('logout', 'Auth\LogoutController@logout')->name('logout');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->middleware('auth');

Route::group(
    [
        'prefix' => 'teacher',
        'as' => 'teacher.',
        'namespace' => 'Teacher',
        'middleware' => ['auth','role:employee']
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
    }
);

Route::group(
    [
        'prefix' => 'student',
        'as' => 'student.',
        'namespace' => 'Student',
        'middleware' => ['auth','role:student']
    ],
    function () {
        Route::view('/', 'student.dashboard')->name('dashboard');
    }
);

Route::view('/upgrade', 'upgrade')->name('upgrade');

Route::post('sync', 'SyncronizeController@store');

Route::get('rapor/{class_id}', [RaporController::class, 'rapor']);
