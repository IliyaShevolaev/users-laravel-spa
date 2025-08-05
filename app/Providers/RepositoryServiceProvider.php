<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\User\Position\PositionRepository;
use App\Repositories\User\Department\DepartmentRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;
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
