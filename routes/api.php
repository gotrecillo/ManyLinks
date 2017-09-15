<?php

use Illuminate\Http\Request;

Route::namespace('Api\Auth')->group(function () {
    Route::post('auth/login', 'LoginController');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
