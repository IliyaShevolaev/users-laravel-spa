<?php

namespace App\Models\Roles;

use Illuminate\Support\Str;
use App\Policies\Roles\RolePolicy;
use Spatie\Activitylog\LogOptions;
use App\Enums\Role\SystemRolesEnum;
use Laratrust\Models\Role as RoleModel;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

class Role extends RoleModel
{
    use LogsActivity;

    protected $fillable = ['name', 'display_name'];
    protected static $recordEvents = ['deleted'];

    protected static function booted(): void
    {
        parent::booted();

        static::updating(function ($role) {
            if (collect(SystemRolesEnum::cases())->pluck('value')->contains($role->getOriginal('name'))) {
                $role->name = $role->getOriginal('name');
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }


    /**
     * Логирование ролей с разрешениями
     *
     * @param string $event
     * @param array|null $oldRole
     * @param array|null $oldPermissions
     * @return void
     */
    public function logRoleActivity(string $event, ?array $oldRole = null, ?array $oldPermissions = null): void
    {
        $this->load('permissions');
        $permissions = $this->permissions()?->pluck('name')->toArray();

        $properties = [
            'attributes' => [
                'name' => $this->name,
                'display_name' => $this->display_name,
            ],
            'permissions' => $permissions,
        ];

        if ($oldRole || $oldPermissions) {
            $properties['old'] = [
                'name' => $oldRole['name'] ?? null,
                'display_name' => $oldRole['display_name'] ?? null,
                'permissions' => $oldPermissions ?? [],
            ];
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($this)
            ->withProperties($properties)
            ->event($event)
            ->log($event);
    }
}
