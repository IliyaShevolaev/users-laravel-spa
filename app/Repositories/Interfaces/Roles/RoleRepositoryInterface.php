<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Roles;

use App\DTO\Roles\CreateRoleDTO;
use App\Models\Roles\Role;
use Illuminate\Database\Eloquent\Builder;

interface RoleRepositoryInterface
{
    /**
     * Создать запись о роли
     *
     * @param CreateRoleDTO $createRoleDTO
     * @return void
     */
    public function create(CreateRoleDTO $createRoleDTO): Role;

    /**
     * Найти роль
     *
     * @param int $roleId
     * @return Builder<Role>|Role
     */
    public function find(int $roleId): Role;

    /**
     * Удалить роль
     *
     * @param Role $role
     * @return void
     */
    public function delete(Role $role): void;

    /**
     * Получить запрос
     *
     * @return Builder<Role>
     */
    public function getQuery(): Builder;
}
