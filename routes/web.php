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
    return view('index');
});

Route::namespace('Auth')->prefix('auth')->as('auth.')->group(function () {
    Route::get('/email-confirmation/error')->name('email-verification.error');
    Route::get('/email-confirmation/{code}', 'ConfirmEmailController')->name('email-verification.check');
});
