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

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/pj/dashboard', 'DashboardController@index');
Route::get('/tempat', 'TempatController@indexDaftar');
Route::get('/detail-tempat', 'TempatController@indexDetail');
Route::get('/tambah-tempat', 'TempatController@indexTambah');

Route::get(['prefix' => '/'])->middleware('status');