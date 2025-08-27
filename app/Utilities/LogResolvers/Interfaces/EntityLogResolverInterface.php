<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers\Interfaces;

use Spatie\Activitylog\Models\Activity;

interface EntityLogResolverInterface
{
    /**
     * Составить массив сообщений на основе лога
     * @param Activity $log
     * @return void
     */
    public static function resolve(Activity $log): array;
}
