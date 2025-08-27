<?php

declare(strict_types=1);

namespace App\Utilities\LogResolvers;

use App\Utilities\LogResolvers\Interfaces\EntityLogResolverInterface;
use Spatie\Activitylog\Models\Activity;

class RoleLogResolver implements EntityLogResolverInterface
{
    public static function getSubjectName(Activity $log): string
    {
        return $log->properties['attributes']['display_name'] ?? $log->properties['old']['display_name'];
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

        foreach ($attributes as $key => $newValue) {
            if ($key == 'name') {
                continue;
            }

            $oldValue = $old[$key] ?? null;

            if ($oldValue === $newValue)
                continue;

            $messages[] = self::getDefaultMessage($key, $newValue, $oldValue);
        }

        $newPermissions = collect($properties['permissions'] ?? [])->sort()->values()->all();
        $oldPermissions = collect($properties['old']['permissions'] ?? [])->sort()->values()->all();

        if ($newPermissions !== $oldPermissions) {
            $messages[] = self::getPermissionsMessage($newPermissions);
        }

        return $messages;
    }

    private static function getDefaultMessage(string $key, ?string $new, ?string $old = null): string
    {
        $fieldName = trans("main.logs.fields.role." . $key);

        if ($old === null) {
            return $fieldName . " установлено на $new";
        }
        if ($new === null) {
            return $fieldName . " изменено с $old на пустое значение";
        }
        return $fieldName . " изменено с $old на $new";
    }

    private static function getPermissionsMessage(array $permissions): string
    {
        $permissionsMessage = collect();

        foreach ($permissions as $permission) {
            $permissionSplit = explode('-', $permission);
            $permissionsMessage->push(
                trans('main.logs.fields.role.permissions.entity.' . $permissionSplit[0]) .
                '-' .
                trans('main.logs.fields.role.permissions.action.' . $permissionSplit[1])
            );
        }

        return 'Разрешения: ' . $permissionsMessage->join(' ');
    }
}
