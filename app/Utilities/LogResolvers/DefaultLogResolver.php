<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use App\Utilities\LogResolvers\Interfaces\EntityLogResolverInterface;
use Spatie\Activitylog\Models\Activity;

class DefaultLogResolver implements EntityLogResolverInterface
{
    public static function resolve(Activity $log): array
    {
        if ($log->event === 'deleted') {
            return ["Запись была удалена"];
        }

        $properties = $log->properties;
        $messages = [];

        $attributes = collect($properties['attributes'] ?? []);
        $old = collect($properties['old'] ?? []);

        foreach ($attributes as $key => $newValue) {
            $oldValue = $old[$key] ?? null;

            if ($oldValue === $newValue)
                continue;

            $messages[] = self::getDefaultMessage($key, $newValue, $oldValue);
        }

        return $messages;
    }

    private static function getDefaultMessage(string $key, ?string $new, ?string $old = null): string
    {
        $fieldName = trans("main.logs.fields.default." . $key);

        if ($old === null) {
            return $fieldName . " установлено на $new";
        }
        if ($new === null) {
            return $fieldName . " изменено с $old на пустое значение";
        }
        return $fieldName . " изменено с $old на $new";
    }
}
