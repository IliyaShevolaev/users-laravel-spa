<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use App\Utilities\LogResolvers\Interfaces\EntityLogResolverInterface;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class EventLogResolver implements EntityLogResolverInterface
{
    public static function getSubjectName(Activity $log): string
    {
        return $log->properties['attributes']['title'] ?? $log->properties['old']['title'];;
    }

    public static function resolve(Activity $log): array
    {
        if ($log->event === 'deleted') {
            return ["Запись была удалена"];
        }

        $properties = $log->properties;
        $messages = [];

        $attributes = collect($properties['attributes'] ?? []);
        $old = collect($properties['old'] ?? []);

        if ($log->event === 'event_mark') {
            return $attributes['mark'] ? ['Отметил исполненным'] : ['Отметил неисполненным'];
        }

        foreach ($attributes as $key => $newValue) {

            $oldValue = $old[$key] ?? null;

            if ($oldValue === $newValue)
                continue;

            if ($key === 'start' || $key === 'end') {
                $newValue = Carbon::parse($newValue)->format('d.m.Y H:i');
                $oldValue = Carbon::parse($oldValue)->format('d.m.Y H:i');
            }

            $messages[] = self::getDefaultMessage($key, $newValue, $oldValue);
        }

        return $messages;
    }

    private static function getDefaultMessage(string $key, ?string $new, ?string $old = null): string
    {
        $fieldName = trans("main.logs.fields.event." . $key);

        if ($old === null) {
            return $fieldName . " установлено на $new";
        }
        if ($new === null) {
            return $fieldName . " изменено с $old на пустое значение";
        }
        return $fieldName . " изменено с $old на $new";
    }
}
