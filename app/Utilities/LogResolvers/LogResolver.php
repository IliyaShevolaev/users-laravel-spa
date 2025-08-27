<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use Spatie\Activitylog\Models\Activity;

class LogResolver
{
    public static function resolveLogMessage(Activity $log): array
    {
        return self::getResolver($log)::resolve($log);
    }

    public static function getSubjectName(Activity $log)
    {
        return self::getResolver($log)::getSubjectName($log);
    }

    private static function getResolver(Activity $log): string
    {
        return match (class_basename($log->subject_type)) {
            'User' => UserLogResolver::class,
            'Role' => RoleLogResolver::class,
            'Event' => EventLogResolver::class,
            default => DefaultLogResolver::class,
        };
    }
}
