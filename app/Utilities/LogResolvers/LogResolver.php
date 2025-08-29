<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class LogResolver
{
    public static function resolveLogMessage(Activity $log): array
    {
        $properties = $log->properties;
        $messages = [];

        $attributes = collect($properties['attributes'] ?? []);
        $old = collect($properties['old'] ?? []);

        if ($log->event === 'created') {
            foreach ($attributes as $key => $value) {
                if (!$value) {
                    continue;
                }

                $messages[] = self::getDefaultMessage($log, $key, $value);
            }
        }

        if ($log->event === 'updated') {
            foreach ($attributes as $key => $newValue) {
                $oldValue = $old[$key] ?? null;

                $messages[] = self::getDefaultMessage($log, $key, $newValue, $oldValue);
            }
        }

        if ($log->event === 'deleted') {
            $messages[] = __('main.logs.deleted_row');
        }

        return $messages;
    }

    private static function getDefaultMessage(
        Activity $log,
        string $key,
        string|bool|null $new,
        string|bool|null $old = null
    ): string {
        $entityName = Str::afterLast($log->subject_type, '\\');

        $fieldName = __("main.logs.fields." . $entityName . '.' . $key);

        $new === true ? $new = 'mark' : $new;
        $new === false ? $new = 'unmark' : $new;
        $new = __("main.logs.values.{$new}") !== "main.logs.values.{$new}" ?
            __("main.logs.values.{$new}") :
            $new;

        $old === true ? $old = 'mark' : $old;
        $old === false ? $old = 'unmark' : $old;
        $old = __("main.logs.values.{$old}") !== "main.logs.values.{$old}" ?
            __("main.logs.values.{$old}") :
            $old;

        if ($old === null) {
            if ($key === 'event.title') {
                return $fieldName . ' ' . $new;
            }
            return $fieldName . ' ' . __('main.logs.set_at') . " $new";
        }
        if ($new === null) {
            return $fieldName . ' ' . __('main.logs.changed_from') . ' ' . $old . ' ' . __('main.logs.on_empty_value');
        }

        return $fieldName . ' ' . __('main.logs.changed_from') . ' ' . $old . __('main.logs.to') . $new;
    }
}
