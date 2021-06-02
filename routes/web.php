<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/login/{provider}/connect', 'SocialAuthController@connect')->where(['provider' => '(facebook|google)'])->name('connect.social');
Route::get('/login/{provider}/callback', 'SocialAuthController@callback');

Route::group(['middleware' => 'auth'], function () {
    Route::redirect('/', '/dashboard');
    Route::get('/disconnect/facebook', 'SocialAuthController@disconnectFacebook')->name('disconnect.facebook');
    Route::get('/disconnect/google', 'SocialAuthController@disconnectGoogle')->name('disconnect.google');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/calendar', 'DashboardController@calendar')->name('calendar');

    Route::prefix('notifications')->group(function () {
        Route::get('/', 'NotificationController@index')->name('notifications.index');
        Route::put('/{notification}', 'NotificationController@read')->name('notifications.read');
        Route::delete('delete/all', 'NotificationController@destroyAll')->name('notifications.destroy.all');
        Route::delete('delete/{notification}', 'NotificationController@destroy')->name('notifications.destroy');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', 'ProfileController@index')->name('profile.index');
        Route::post('/', 'ProfileController@update')->name('profile.update');
        Route::post('password', 'ProfileController@updatePassword')->name('profile.update.password');
        Route::get('session/destroy/{id}', 'ProfileController@destroySession')->name('profile.session.destroy');
    });

    Route::prefix('media')->group(function () {
        Route::get('/', 'MediaController@index')->name('media.index');
        Route::post('/', 'MediaController@store')->name('media.store');
        Route::get('original/{mediaName}', 'MediaController@showOriginal')->name('media.show.original');
        Route::get('thumb/{mediaName}', 'MediaController@showThumb')->name('media.show.thumb');
        Route::delete('delete/{media}', 'MediaController@destroy')->name('media.destroy');
    });

    Route::prefix('accounts')->where(['platform' => '(facebook|twitter)'])->group(function () { //will add |instagram later ...
        Route::get('/', 'AccountController@index')->name('accounts.index');
        Route::get('{platform}/connect', 'AccountController@connect')->name('accounts.connect');
        Route::get('{platform}/callback', 'AccountController@callback')->name('accounts.connect.callback');
        Route::delete('delete/{account}', 'AccountController@destroy')->name('accounts.destroy');
    });

    Route::resource('posts', 'PostController');

});
