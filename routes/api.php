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

Route::namespace('Auth')->as('auth.')->group(function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('me', 'AuthController@me')->name('me');
});

Route::prefix('groups')->as('groups.')->group(function () {
    Route::get('/', 'GroupController@index')->name('index');
    Route::get('/{group}/algs', 'GroupController@algs')->name('algs')->where(['group' => '[0-9]+']);
});

Route::prefix('algs')->as('algs.')->group(function () {
    Route::get('/count', 'AlgController@count')->name('count');
});
