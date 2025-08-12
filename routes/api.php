<?php

declare(strict_types=1);

// php vendor/bin/phpstan analyse -c phpstan.neon
// php vendor/bin/phpcs

use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DepartmentController;

Route::get('/auth/create', '\App\Http\Controllers\Auth\RegisterController@create');

Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\User\UserController@index');

    Route::get('/users/datatable', [UserController::class, 'datatable']);
    Route::resource('users', UserController::class);

    Route::get('/departments/datatable', [DepartmentController::class, 'datatable']);
    Route::resource('departments', DepartmentController::class);

    Route::get('/positions/datatable', [PositionController::class, 'datatable']);
    Route::resource('positions', PositionController::class);

    Route::get('/roles/datatable', [RoleController::class, 'datatable']);
    Route::resource('roles', RoleController::class);
});
Route::get('/user', function (Request $request) {
    return Auth::user();
});

