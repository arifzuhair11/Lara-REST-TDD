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

Route::post('/login', 'AuthController@login')->name('api.login');

Route::group(['middleware' => 'auth:api'], function (){
});

Route::post('/anime', 'API\AnimeController@store');
Route::get('/anime/{anime}', 'API\AnimeController@show');
Route::put('/anime/{anime}', 'API\AnimeController@update');
