<?php

declare(strict_types=1);

// php vendor/bin/phpstan analyse -c phpstan.neon
// php vendor/bin/phpcs

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DepartmentController;

Route::get('/auth/create', '\App\Http\Controllers\Auth\RegisterController@create');

Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\User\UserController@index');

    Route::resource('users', \App\Http\Controllers\User\UserController::class);

    Route::get('/departments/datatable', [DepartmentController::class, 'getDatatable'])->name('departments.datatable');
    Route::resource('departments', \App\Http\Controllers\User\DepartmentController::class);

    Route::resource('positions', \App\Http\Controllers\User\PositionController::class);
});
Route::get('/user', function (Request $request) {
    return Auth::user();
});

