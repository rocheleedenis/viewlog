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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/single', 'LogController@single');
Route::get('getdata', 'LogController@getData')->name('getdata');

Route::get('/storage', 'LogController@storage');
Route::get('/data', 'LogController@data')->name('data');
