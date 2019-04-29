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
Route::get('profile/{id}', 'APIPenggunaController@getProfile');
Route::get('admin', 'APIPenggunaController@getAllAdmin');

Route::get('bidang', 'APIBidangController@getAllBidang');
Route::get('bidang/rating/{idRating}', 'APIBidangController@getRatingBidang');
Route::get('bidang/info/{idInfo}', 'APIBidangController@getInfoBidang');
Route::get('bidang/tempat/{idTempat}', 'APIBidangController@getTempatBidang');

Route::get('keahlian', 'APIKeahlianController@getAllKeahlian');
Route::get('keahlian/rating/{idRating}', 'APIKeahlianController@getRatingKeahlian');
Route::get('keahlian/info/{idInfo}', 'APIKeahlianController@getInfoKeahlian');
Route::get('keahlian/tempat/{idTempat}', 'APIKeahlianController@getTempatKeahlian');

Route::get('ps', 'APIPSController@getAllPS');

Route::get('tempat', 'APITempatController@getAllTempat');
Route::post('tempat', 'APITempatController@addTempat');
Route::put('tempat', 'APITempatController@editTempat');
Route::post('tempat/detail', 'APITempatController@getDetailTempat');
Route::get('tempat/my/{idUser}', 'APITempatController@getMyTempat');
Route::post('tempat/rekomendasi', 'APITempatController@getRekomendasi');
Route::post('cobaupload', 'APITempatController@cobaUpload');
Route::get('tempat/{id}', 'APITempatController@getDataTempat');
Route::get('search', 'APITempatController@searchTempat');

Route::get('info/tempat/{idTempat}', 'APIInfoController@getInfoTempat');
Route::get('info/{id}', 'APIInfoController@getDetailInfo');
Route::post('info', 'APIInfoController@addInfo');
Route::put('info', 'APIInfoController@editInfo');
Route::delete('info/{id}', 'APIInfoController@deleteInfo');
Route::get('info/my/{idUser}', 'APIInfoController@getMyInfo');

Route::get('rating/tempat/{idRating}', 'APIRatingController@getRatingTempat');
Route::get('rating/{id}', 'APIRatingController@getDetailRating');
Route::post('rating', 'APIRatingController@addRating');
Route::put('rating', 'APIRatingController@editRating');
Route::delete('rating/{id}', 'APIRatingController@deleteRating');
Route::get('rating/my/{idUser}', 'APIRatingController@getMyRating');

Route::post('bookmark', 'APIBookmarkController@addBookmark');
Route::delete('bookmark', 'APIBookmarkController@deleteBookmark');
Route::get('bookmark/my/{idUser}', 'APIBookmarkController@getMyBookmark');

Route::get('tipe', 'APITipeController@getAllTipe');

Route::post('pertanyaan', 'APIPertanyaanController@addPertanyaan');
Route::get('pertanyaan/my/{idUser}', 'APIPertanyaanController@getMyPertanyaan');
Route::get('pertanyaan/{idChat}', 'APIPertanyaanController@getDetailPertanyaan');
Route::post('jawaban', 'APIPertanyaanController@addJawaban');
Route::delete('pertanyaan/{idChat}', 'APIPertanyaanController@deleteMyPertanyaan');