<?php

declare(strict_types=1);

namespace App\Repositories\Roles;

use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function create(CreateRoleDTO $createRoleDTO): void
    {
        Role::create($createRoleDTO->all());
    }

    public function getQuery(): Builder
    {
        return Role::query();
    }
}
