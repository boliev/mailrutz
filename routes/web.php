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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'whitelist:trusted'], function () {
    Route::get('/streams', 'StreamController@index')->name('getStreams')->middleware('auth:api');
    Route::get('/streams/games', 'StreamController@byGames')->name('getStreamsByGames')->middleware('auth:api');
});
