<?php

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

/////////////////////Login/////////////////////////
Route::group(['middleware'=>'auth:api'],function (){
    Route::post('login',[
        'uses' => 'Auth\LoginController@login',
        'as' => 'login'
    ]);
});

Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::options('/login', function () {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS, POST, PUT',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers',
    ]);
});

//////////////////////User/////////////////////////
Route::get('/users/{user}', 'UserController@index');
Route::post('/users/create', 'UserController@create'); //create

////////////////////Customer/////////////////////////
Route::get('/customers/{customer}', 'CustomerController@index'); //index