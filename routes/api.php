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

Route::post('login', 'HomeController@login');
Route::post('register', 'UserController@store');

Route::get('teste', 'UserController@teste');
Route::post('confirm-verification/verification_code', 'HomeController@verifyUser');

Route::group(['middleware' => 'jwt.auth'], function () {
    
    Route::get('ping', 'HomeController@ping');
    Route::get('getAuthUser', 'HomeController@getUserLogged');

    //routes of users
    Route::resource('user', 'UserController', ['except' => [
        'store', 'index'
    ]]);
    Route::post('user/update-profile-picture/{user}', 'UserController@updateProfilePicture');

    //routes of events
    Route::resource('event', 'EventController');
    Route::post('add-artist/event/{event}', 'EventController@addArtistToEvent');
    Route::post('confirm-artist/event/{event}', 'EventController@confirmArtistToEvent');
});

