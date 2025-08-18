<?php

namespace App\Models\Roles;

use Illuminate\Support\Str;
use App\Policies\Roles\RolePolicy;
use Laratrust\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

class Role extends RoleModel
{
    protected $fillable = ['name', 'display_name'];
}
