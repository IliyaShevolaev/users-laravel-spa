<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use App\Utilities\LogResolvers\Interfaces\EntityLogResolverInterface;
use Spatie\Activitylog\Models\Activity;

class UserLogResolver implements EntityLogResolverInterface
{
    public static function getSubjectName(Activity $log): string
    {
        return $log->properties['attributes']['name'] ?? $log->properties['old']['name'];;
    }

    public static function resolve(Activity $log): array
    {
        $properties = $log->properties;
        $messages = [];

        $attributes = collect($properties['attributes'] ?? []);
        $old = collect($properties['old'] ?? []);

        if ($log->event === 'created') {
            foreach ($attributes as $key => $value) {
                if (!$value || $key === 'password') {
                    continue;
                }

                $messages[] = self::getDefaultMessage($key, $value);
            }
        }

        if ($log->event === 'updated') {
            foreach ($attributes as $key => $newValue) {
                $oldValue = $old[$key] ?? null;

                if ($oldValue === $newValue)
                    continue;

                if ($key === 'password') {
                    $messages[] = 'Пароль был изменен';
                    continue;
                }

                $messages[] = self::getDefaultMessage($key, $newValue, $oldValue);
            }
        }

        if ($log->event === 'deleted') {
            $messages[] = "Запись была удалена";
        }

        return $messages;
    }

    private static function getDefaultMessage(string $key, ?string $new, ?string $old = null): string
    {
        if ($key === 'gender' || $key === 'status') {
            $new = $new ? trans("main.logs.fields.user.field_values." . $new) : $new;
            $old = $old ? trans("main.logs.fields.user.field_values." . $old) : $old;
        }

        $fieldName = trans("main.logs.fields.user." . $key);

        if ($old === null) {
            return $fieldName . " установлено на $new";
        }
        if ($new === null) {
            return $fieldName . " изменено с $old на пустое значение";
        }
        return $fieldName . " изменено с $old на $new";
    }
}
