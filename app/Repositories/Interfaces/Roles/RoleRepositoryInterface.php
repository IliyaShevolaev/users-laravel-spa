<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Roles;

use App\DTO\Roles\CreateRoleDTO;
use App\Models\Roles\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface RoleRepositoryInterface
{

    /**
     * Получить дто всех записей
     *
     * @return Collection
     */
    public function all(): Collection;
    /**
     * Создать запись о роли
     *
     * @param CreateRoleDTO $createRoleDTO
     * @return void
     */
    public function create(CreateRoleDTO $createRoleDTO): Role;

    /**
     * Обновить запись о роли
     * @param CreateRoleDTO $dto
     * @param Role $role
     * @return void
     */
    public function update(CreateRoleDTO $dto, Role $role): void;

    /**
     * Найти роль
     *
     * @param int $roleId
     * @return Role
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
     * Количество ролей
     *
     * @return int
     */
    public function count(): int;

    /**
     * Получить запрос
     *
     * @return Builder<Role>
     */
    public function getQuery(): Builder;

    /**
     * Найти пользователей с ролью
     *
     * @param Role $role
     * @return Collection
     */
    public function findRelatedUsers(Role $role): Collection;

    /**
     * Найти роль подгрузив разрешения
     *
     * @param int $roleId
     * @return Role
     */
    public function findWithPermissions(int $roleId): Role;
}
