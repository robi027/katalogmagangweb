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

Route::post('register', 'APIPenggunaController@register');
Route::put('register', 'APIPenggunaController@complete');
Route::post('login', 'APIPenggunaController@login');

Route::get('bidang', 'APIBidangController@getAllBidang');

Route::get('keahlian', 'APIKeahlianController@getAllKeahlian');

Route::get('ps', 'APIPSController@getAllPS');