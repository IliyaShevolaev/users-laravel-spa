<?php

namespace App\Providers;

use App\Models\User;
use App\DTO\User\UserDTO;
use App\Models\Roles\Role;
use App\Enums\Role\SystemRolesEnum;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use App\DTO\Tasks\Event\CreateEventDTO;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
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
        Gate::define('check-permission', function (User $user, string $permission) {
            return $user->hasPermission($permission);
        });
        Gate::define('change-user', function (User $user) {
            return $user->hasPermission('users-create') || $user->hasPermission('users-update');
        });
        Gate::define('delete-user', function (User $user, User $deleteUser) {
            return $user->hasPermission('users-delete') &&
                $deleteUser->roles()->first()->name !== SystemRolesEnum::System->value;
        });
        Gate::define('delete-role', function (User $user, Role $role) {
            return $user->hasPermission('roles-delete') &&
                collect(SystemRolesEnum::cases())->pluck('value')->doesntContain($role->name);
        });

        Gate::guessPolicyNamesUsing(function (string $class) {
            if ($class === CreateEventDTO::class) {
                return EventPolicy::class;
            }
            return null;
        });
    }
}
