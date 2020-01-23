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

// User account routes
Route::get('user', 'UserController@show')->name('user');
Route::post('user/update', 'UserController@update')->name('user.update');
Route::get('/user/delete','UserController@destroy')->name('user.destroy');

