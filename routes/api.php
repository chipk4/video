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

Route::group(['prefix' => 'v0.1', 'as' => 'api.'], function () {
    Route::post('auth/login', 'AuthController@login')->name('login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('video/upload', 'VideoController@upload')->name('video_upload');
        Route::get('video/list', 'VideoController@getList')->name('video.lost');
    });
});