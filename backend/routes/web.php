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

Route::get('/', 'HomeController@index')->name('welcome');
Route::post('/', 'HomeController@capture');

Route::get('/{directory}', 'HomeController@comment')->name('comment');
Route::post('/{directory}', 'HomeController@commentCreate');

Auth::routes();

Route::get('/home', 'UserController@index')->name('home');
