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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('groups')->as('groups.')->group(function () {
    Route::get('/', 'GroupController@index')->name('index');
    Route::get('/{group}/algs', 'GroupController@algs')->name('algs')->where(['group' => '[0-9]+']);
});
