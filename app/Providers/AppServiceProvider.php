<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;
use Yajra\DataTables\Html\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        HeadingRowFormatter::extend('slugFormatter', function($value, $key) {
            return Str::slug($value);
        });

        HeadingRowFormatter::default('slugFormatter');
    }
}
