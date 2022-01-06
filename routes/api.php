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

// Route::options('/login', function () {
//     return response()->json([], 200, [
//         'Access-Control-Allow-Origin' => '*',
//         'Access-Control-Allow-Methods' => 'GET, OPTIONS, POST, PUT',
//         'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers',
//     ]);
// });

////////////////////// User /////////////////////////
Route::get('/users', 'UserController@index'); //index
Route::get('/users/{user}', 'UserController@show'); //show
Route::put('/users/{user}', 'UserController@update'); //update
Route::post('/users/create', 'UserController@create'); //create
Route::delete('/users/{user}', 'UserController@destroy'); //delete
Route::get('/users/management/unapproved_managers', 'UserController@getUnApprovedManagers'); //get
Route::put('/users/approve/{user}', 'UserController@approve_manager'); //put

//////////////////// Movie /////////////////////////
Route::get('/movies/all', 'MovieController@index'); //index
Route::post('/movie/create', 'MovieController@create'); //create
Route::get('/movie/show/{movie}', 'MovieController@show'); //show
Route::put('/movie/update/{movie}', 'MovieController@update'); //update
Route::delete('/movie/delete/{movie}', 'MovieController@destroy'); //delete

//////////////////////User Reservation////////////////////////////
Route::get('/moviereservations/reservation/{user}', 'MovieReservationController@getUserReservation');
Route::put('/moviereservations/reserve/{user}', 'MovieReservationController@addUserReservation');
Route::put('/moviereservations/delete/{user}', 'MovieReservationController@deleteUserReservation');

////////////////////// Movie Reservation /////////////////////////
Route::get('/moviereservations', 'MovieReservationController@index'); //index
Route::get('/moviereservations/getReservations/{movie}', 'MovieReservationController@getMovieReservations'); //index
Route::post('/moviereservations/create', 'MovieReservationController@create'); //create
Route::get('/moviereservations/{moviereservation}', 'MovieReservationController@show'); //show
Route::put('/moviereservations/{moviereservation}', 'MovieReservationController@update'); //update
Route::delete('/moviereservations/{moviereservation}', 'MovieReservationController@destroy'); //delete