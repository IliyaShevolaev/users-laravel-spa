<?php

namespace App\Providers;

use App\Repositories\Cities\CityRepository;
use App\Repositories\Files\FileTemplateRepository;
use App\Repositories\Images\ImageRepository;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;
use App\Repositories\Interfaces\Images\ImageRepositoryInterface;
use App\Repositories\Interfaces\User\UserDocumentRepositoryInterface;
use App\Repositories\User\UserDocumentRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Tasks\EventRepository;
use App\Repositories\Cities\RegionRepository;
use App\Repositories\User\Position\PositionRepository;
use App\Repositories\ActivityLogs\ActivityLogRepository;
use App\Repositories\User\Department\DepartmentRepository;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Repositories\Interfaces\Cities\RegionRepositoryInterface;
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
        ActivityLogRepositoryInterface::class => ActivityLogRepository::class,
        RegionRepositoryInterface::class => RegionRepository::class,
        CityRepositoryInterface::class => CityRepository::class,
        FileTemplateRepositoryInterface::class => FileTemplateRepository::class,
        UserDocumentRepositoryInterface::class => UserDocumentRepository::class,
        ImageRepositoryInterface::class => ImageRepository::class,
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
