<?php

namespace App\Models\Roles;

use Illuminate\Support\Str;
use App\Policies\Roles\RolePolicy;
use App\Enums\Role\SystemRolesEnum;
use Laratrust\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

class Role extends RoleModel
{
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
}
