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

Route::get('/', 'CitiesController@index')->name('cities-home');
Route::post('/near-cities', 'CitiesController@getNearCities')->name('getNearCities');

Route::get('/test', 'CitiesController@test')->name('test');