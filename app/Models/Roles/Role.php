<?php

namespace App\Models\Roles;

use Spatie\Activitylog\LogOptions;
use App\Enums\Role\SystemRolesEnum;
use Laratrust\Models\Role as RoleModel;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends RoleModel
{
    use LogsActivity;

    protected $fillable = ['name', 'display_name'];

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
        return LogOptions::defaults()->logFillable()->logExcept(['name'])->logOnlyDirty()->useLogName($this->display_name);
    }
}
