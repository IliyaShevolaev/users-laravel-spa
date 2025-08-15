<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Roles\Role;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('delete-user', function (User $user) {
            return $user->roles()?->first()->system;
        });
        Gate::define('delete-role', function (User $user, Role $role) {
            return $user->hasPermission('roles-delete') && !$role->system;
        });
    }
}
