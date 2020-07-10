<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('me', 'User\MeController@getMe');

// get design
Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/{id}', 'Designs\DesignController@find_design');
Route::get('designs/slug/{slug}', 'Designs\DesignController@find_by_slug');

// get users
Route::get('users', 'User\UserController@index');
Route::get('users/{id}/designs', 'Designs\DesignController@show');
Route::get('user/{username}', 'User\UserController@find_by_username');

Route::group(['middleware' => ['guest:api']], function () {
    // authentication routes
    Auth::routes(['verify' => true]);
});

Route::group(['middleware' => ['auth:api']], function () {
    // user routes
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@update_password');

    // design routes
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::get('designs/{id}/by-user', 'Designs\DesignController@user_owns_design');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

    // comments routes
    Route::post('designs/{id}/comments', 'Designs\CommentController@store');
    Route::put('comments/{id}', 'Designs\CommentController@update');
    Route::delete('comments/{id}', 'Designs\CommentController@destroy');

});
