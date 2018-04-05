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

Route::get('/', 'HomeController@index');

Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('user', 'UserController', ['except' => [
        'store',
    ]]);

    Route::resource('type-user', 'TypeUserController');
});

