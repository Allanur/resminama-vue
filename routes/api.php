<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Api\AuthController@authenticate');

Route::apiResource('papers', 'Api\PaperController')->only('index', 'show');
Route::get('media/{media}/download', 'Api\MediaController@download')->name('media.download');

Route::middleware('auth:api')->group(function () {

    Route::apiResource('papers', 'Api\PaperController')->except('index', 'show');
    Route::apiResource('media', 'Api\MediaController')->only('store', 'destroy');
});

