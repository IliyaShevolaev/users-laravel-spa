<?php

declare(strict_types=1);

// php vendor/bin/phpstan analyse -c phpstan.neon
// php vendor/bin/phpcs

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/auth/create', '\App\Http\Controllers\Auth\RegisterController@create');

Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\User\UserController@index');

    Route::resource('users', \App\Http\Controllers\User\UserController::class);
    Route::resource('departments', \App\Http\Controllers\User\DepartmentController::class);
    Route::resource('positions', \App\Http\Controllers\User\PositionController::class);
});
Route::get('/user', function (Request $request) {
    return Auth::user();
});

