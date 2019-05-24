<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/')->middleware('status');
Route::get('/login', 'LoginController@index')->name('login')->middleware('guest');
Route::post('/login', 'LoginController@login')->middleware('guest');
Route::get('/logout', 'LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function(){

    Route::get('/admin/dashboard', 'DashboardAdminController@index');
    Route::get('/pj/dashboard', 'DashboardPJController@index');

    Route::get('/tempat', 'TempatController@indexDaftar');
    Route::post('/tempat', 'TempatController@getAllData');
    Route::put('/tempat', 'TempatController@editTempat');
    Route::get('/detail-tempat/{id}', 'TempatController@indexDetail');
    Route::get('/tambah-tempat', 'TempatController@indexTambah');
    Route::get('/update-tempat/{id}', 'TempatController@indexUpdate');
    Route::get('/review-tempat/{id}', 'TempatController@indexReview');

    Route::post('/listInfo', 'InfoController@getInfoTempat');
    Route::get('/info/{id}', 'InfoController@getInfo');

    Route::post('/listRating', 'RatingController@getRatingTempat');
    Route::get('/rating/{id}', 'RatingController@getRating');

    Route::get('/pertanyaan', 'PertanyaanController@indexDaftar');
    Route::get('/list-pertanyaan', 'PertanyaanController@getMorePertanyaan');
    Route::get('/detail-pertanyaan/{idChat}', 'PertanyaanController@detailPertanyaan');

    Route::get('/keahlian', 'KeahlianController@indexDaftar');
    Route::post('/keahlian', 'KeahlianController@getAllData');
    Route::post('/tambah-keahlian', 'KeahlianController@addKeahlian');
    Route::put('/keahlian', 'KeahlianController@editKeahlian');

    Route::get('/bidang', 'BidangController@indexDaftar');
    Route::post('/bidang', 'BidangController@getAllData');
    Route::post('/tambah-bidang', 'BidangController@addBidang');
    Route::put('/bidang', 'BidangController@editBidang');
});