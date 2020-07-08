<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['guest:api']], function () {
    // authentication routes
    Auth::routes(['verify' => true]);
});

Route::group(['middleware' => ['auth:api']], function () {
    // user routes
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('me', 'User\MeController@getMe');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@update_password');

    // design routes
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
});
