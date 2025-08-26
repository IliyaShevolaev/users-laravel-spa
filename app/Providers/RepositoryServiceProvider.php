<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Tasks\EventRepository;
use App\Repositories\User\Position\PositionRepository;
use App\Repositories\ActivityLogs\ActivityLogRepository;
use App\Repositories\User\Department\DepartmentRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;
use App\Repositories\Interfaces\ActivityLogs\ActivityLogRepositoryInterface;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Все связывания контейнера, которые должны быть зарегистрированы.
     *
     * @var array<mixed>
     */
    public $bindings = [
        DepartmentRepositoryInterface::class => DepartmentRepository::class,
        PositionRepositoryInterface::class => PositionRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        EventRepositoryInterface::class => EventRepository::class,
        ActivityLogRepositoryInterface::class => ActivityLogRepository::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
