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

Route::get('/user', 'UserController@index');
Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

Route::group(['middleware' => 'jwt.auth'], function () {
    
    //routes of users
    Route::resource('user', 'UserController', ['except' => [
        'store', 'index'
    ]]);
    
    //routes of events
    Route::resource('event', 'EventController');
    Route::post('add-artist/event/{event}/artist', 'EventController@addArtistToEvent');
    Route::post('confirm-artist/event/{event}/artist/{artist}', 'EventController@confirmArtistToEvent');
});

