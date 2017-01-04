<?php

use Illuminate\Http\Request;

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

Route::post('/user', 'UserController@create');
Route::post('/login', 'UserController@login');

Route::group(['middleware' => 'jwt.auth'], function () {

    // House related routes
    Route::resource('house', 'HouseController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::get('/user/house', 'HouseController@currentHouse');

    // Task related routes
    Route::resource('room', 'RoomController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

    // Room related routes
});
