<?php

declare(strict_types=1);

namespace App\Repositories\Roles;

use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function create(CreateRoleDTO $createRoleDTO): Role
    {
        return Role::create($createRoleDTO->all());
    }

    public function update( CreateRoleDTO $dto, Role $role): void
    {
        $role->update($dto->all());
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function find(int $roleId): Role
    {
        return Role::find($roleId);
    }


    public function getQuery(): Builder
    {
        return Role::query();
    }
}
