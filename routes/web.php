<?php

use Illuminate\Support\Facades\Route;

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

// Tag
Route::post('app/tambah_tag', 'AdminController@tambahTag');
Route::get('app/get_tag', 'AdminController@getTag');
Route::post('app/edit_tag', 'AdminController@editTag');
Route::post('app/hapus_tag', 'AdminController@hapusTag');

// Categori
Route::post('app/upload', 'AdminController@uploadImgCategori');
Route::post('app/hapus_gambar', 'AdminController@hapusGambar');
Route::post('app/tambah_categori', 'AdminController@tambahCategori');
Route::get('app/get_categori', 'AdminController@getCategori');
Route::post('app/edit_categori', 'AdminController@editCategori');

Route::get('/', function () {
    return view('welcome');
});

Route::any('{slug}', function () {
    return view('welcome');
});
