<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use Spatie\Activitylog\Models\Activity;

class LogResolver
{
    public static function resolveLogMessage(Activity $log): array
    {
        $subjectType = class_basename($log->subject_type);

        return match ($subjectType) {
            'User' => UserLogResolver::resolve($log),
            default => DefaultLogResolver::resolve($log),
        };
    }
}
