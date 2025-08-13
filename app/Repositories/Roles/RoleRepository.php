<?php

declare(strict_types=1);

namespace App\Repositories\Roles;

use App\DTO\User\UserDTO;
use App\DTO\Roles\RoleDTO;
use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function all(): Collection
    {
        return RoleDTO::collect(Role::where('system', false)->get());
    }

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
        return Role::where('system', false)->find($roleId);
    }


    public function getQuery(): Builder
    {
        return Role::query()->where('system', false);
    }
}
