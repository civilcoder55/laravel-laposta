<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\NotificationController;



Auth::routes();
Route::get('/login/{driver}', [SocialAuthController::class, 'redirectToDrive'])->name('login.social');
Route::get('/login/{driver}/redirect', [SocialAuthController::class, 'redirectionHandler']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/revoke/facebook', [SocialAuthController::class, 'revokeFacebook'])->name('revoke.facebook');
    Route::get('/revoke/google', [SocialAuthController::class, 'revokeGoogle'])->name('revoke.google');
    Route::get('{path}', [HomeController::class, 'index'])->where('path', '(home|)')->name('home');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

});


Route::middleware(['auth'])->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('delete/all', [NotificationController::class, 'destroyAll'])->name('notifications.destroy.all');
    Route::delete('delete/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');


});

Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::get('session/destroy/{id}', [ProfileController::class, 'destroy'])->name('profile.session.destroy');
});

Route::middleware(['auth'])->prefix('media')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('media.index');
    Route::post('/', [MediaController::class, 'store'])->name('media.store');
    Route::get('original/{mediaName}', [MediaController::class, 'showOriginal'])->name('media.show.original');
    Route::get('thumb/{mediaName}', [MediaController::class, 'showThumb'])->name('media.show.thumb');
    Route::post('delete/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
});

Route::middleware(['auth'])->prefix('accounts')->where(['platform' => '(facebook)'])->group(function () { //will add |twitter|instagram later ... 
    Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('{platform}/add', [AccountController::class, 'redirectToPlatform'])->name('account.add');
    Route::get('{platform}/redirect', [AccountController::class, 'redirectionHandler'])->name('account.redirection.handler');
    Route::delete('{platform}/delete/{account}', [AccountController::class, 'destroy'])->name('account.destroy');
});

Route::middleware(['auth'])->prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});
