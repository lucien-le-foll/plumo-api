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

    // User related routes
    Route::get('/user/me', 'UserController@me');
    Route::get('/user/{query}', 'UserController@search')->where('query', '\w+');
    Route::put('/user', 'UserController@update');

    // House related routes
    Route::resource('house', 'HouseController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::get('/house/leave', 'HouseController@leave');
    Route::get('/house/join/{id}', 'HouseController@join');

    // Room related routes
    Route::resource('room', 'RoomController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

    // Task related routes
    Route::resource('task', 'TaskController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    Route::get('/task/perform/{id}', 'TaskController@perform');
});
