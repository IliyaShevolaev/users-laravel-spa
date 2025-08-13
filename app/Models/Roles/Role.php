<?php

namespace App\Models\Roles;

use App\Policies\Roles\RolePolicy;
use Laratrust\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

class Role extends RoleModel
{
    protected $fillable = ['name'];
}
