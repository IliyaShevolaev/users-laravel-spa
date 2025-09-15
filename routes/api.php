<?php

declare(strict_types=1);

// php vendor/bin/phpstan analyse -c phpstan.neon
// php vendor/bin/phpcs

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Cities\CityController;
use App\Http\Controllers\Tasks\EventController;
use App\Http\Controllers\Files\ExportController;
use App\Http\Controllers\Cities\RegionController;
use App\Http\Controllers\Gallery\ImageController;
use App\Http\Controllers\User\PositionController;
use App\Http\Controllers\User\DepartmentController;
use App\Http\Controllers\Files\FileTemplateController;
use App\Http\Controllers\User\UserDocumentsController;
use App\Http\Controllers\ActivityLogs\ActivityLogsController;

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

    Route::post('/regions/datatable', [RegionController::class, 'datatable']);
    Route::resource('regions', RegionController::class);

    Route::post('/cities/datatable', [CityController::class, 'datatable']);
    Route::post('/cities/import', [CityController::class, 'import']);
    Route::get('/cities/export', [CityController::class, 'export']);
    Route::resource('cities', CityController::class);

    Route::group(['prefix' => 'exports'], function () {
        Route::get('/download/{fileName}', [ExportController::class, 'get']);
        Route::get('/mark-downloaded/{fileName}', [ExportController::class, 'mark']);
        Route::get('/get-miss-downloaded-files', [ExportController::class, 'getFiles']);
    });

    Route::group(['prefix' => 'files'], function () {
        Route::post('/templates/datatable', [FileTemplateController::class, 'datatable']);
        Route::post('/templates/generate-document', [FileTemplateController::class, 'generateDocument']);
        Route::resource('templates', FileTemplateController::class);

        Route::get('user/documents/by-user/{user}', [UserDocumentsController::class, 'getByUser']);
        Route::post('user/documents/datatable/{user}', [UserDocumentsController::class, 'datatable']);
        Route::resource('user/documents', UserDocumentsController::class);
    });

});

Route::resource('images', ImageController::class);

Route::get('/user', AuthController::class);
