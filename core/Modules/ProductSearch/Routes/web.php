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

Route::prefix('productsearch')->group(function () {
    Route::get('/', 'ProductSearchController@index');
    Route::get('/invoke/{name?}', 'ProductSearchController@invoke');
    Route::post('/store', 'ProductSearchController@store');
});
