<?php

declare(strict_types=1);

// php vendor/bin/phpstan analyse -c phpstan.neon
// php vendor/bin/phpcs

use App\Events\ChatMessage;
use App\Http\Controllers\ActivityLogs\ActivityLogsController;
use App\Http\Controllers\Tasks\EventController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\User\DepartmentController;

Route::get('/auth/create', '\App\Http\Controllers\Auth\RegisterController@create');

Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\User\UserController@index');

    Route::post('/users/datatable', [UserController::class, 'datatable']);
    Route::get('/users/role/{user}', [UserController::class, 'getUserRole']);
    Route::resource('users', UserController::class);

    Route::post('/departments/datatable', [DepartmentController::class, 'datatable']);
    Route::resource('departments', DepartmentController::class);

    Route::post('/positions/datatable', [PositionController::class, 'datatable']);
    Route::resource('positions', PositionController::class);

    Route::post('/roles/datatable', [RoleController::class, 'datatable']);
    Route::resource('roles', RoleController::class);

    Route::get('/events/amount-stats', [EventController::class, 'amountStats']);
    Route::get('/events/time-stats', [EventController::class, 'amountTimeStats']);
    Route::patch('/events/patch/{event}', [EventController::class, 'patch']);
    Route::post('/events/mark/{event}', [EventController::class, 'mark']);
    Route::post('/events/unmark/{event}', [EventController::class, 'unmark']);
    Route::resource('events', EventController::class);

    Route::post('/activity-logs/datatable/{user}', [ActivityLogsController::class, 'datatable']);
    Route::resource('activity-logs', ActivityLogsController::class);
});

Route::get('/user', AuthController::class);
