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

Route::post('/register', 'API\AuthController@customRegister');


Route::post('/devTo', 'API\AuthController@devToLogin');
Route::post('/myLogin', 'API\AuthController@myLogin');
Route::post('/andreLogin', 'API\AuthController@andreLogin');

Route::group(['middleware' => 'auth:api'], function (){
    Route::get('/anime', 'API\AnimeController@index');
    Route::post('/anime', 'API\AnimeController@store');
    Route::get('/anime/{anime}', 'API\AnimeController@show');
    Route::put('/anime/{anime}', 'API\AnimeController@update');
    Route::delete('/anime/{anime}', 'API\AnimeController@destroy');
});
