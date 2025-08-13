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
        Gate::define('viewAny-user', function (User $user) {
            return $user->hasPermission('users-read');
        });
        Gate::define('create-user', function (User $user) {
            return $user->hasPermission('users-create');
        });
        Gate::define('update-user', function (User $user) {
            return $user->hasPermission('users-update');
        });
        Gate::define('delete-user', function (User $user) {
            return $user->hasPermission('users-delete');
        });
        Gate::define('change-user', function (User $user) {
            return $user->hasPermission('users-create') || $user->hasPermission('users-update');
        });

        Gate::define('viewAny-department', function (User $user) {
            return $user->hasPermission('departments-read');
        });
        Gate::define('create-department', function (User $user) {
            return $user->hasPermission('departments-create');
        });
        Gate::define('update-department', function (User $user) {
            return $user->hasPermission('departments-update');
        });
        Gate::define('delete-department', function (User $user) {
            return $user->hasPermission('departments-delete');
        });

        Gate::define('viewAny-position', function (User $user) {
            return $user->hasPermission('positions-read');
        });
        Gate::define('create-position', function (User $user) {
            return $user->hasPermission('positions-create');
        });
        Gate::define('update-position', function (User $user) {
            return $user->hasPermission('positions-update');
        });
        Gate::define('delete-position', function (User $user) {
            return $user->hasPermission('positions-delete');
        });

        Gate::define('viewAny-role', function (User $user) {
            return $user->hasPermission('roles-read');
        });
        Gate::define('create-role', function (User $user) {
            return $user->hasPermission('roles-create');
        });
        Gate::define('update-role', function (User $user, Role $role) {
            return $user->hasPermission('roles-update') && !$role->system;
        });
        Gate::define('delete-role', function (User $user, Role $role) {
            return $user->hasPermission('roles-delete') && !$role->system;
        });
    }
}
