<?php

declare(strict_types=1);

namespace App\Services\Roles;

use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RoleService
{
    /**
     * Реаозиторий для взаимодействия с данными
     *
     * @var RoleRepositoryInterface $repository
     */
    public function __construct(private RoleRepositoryInterface $repository)
    {
    }

    /**
     * Создать роль
     *
     * @param CreateRoleDTO $createRoleDTO
     * @return void
     */
    public function store(CreateRoleDTO $createRoleDTO): void
    {
        $createdRole = $this->repository->create($createRoleDTO);

        if (!empty($createRoleDTO->permissions)) {
            $createdRole->syncPermissions($createRoleDTO->permissions);
        }
    }

    /**
     * Получить данные о роли для редактирования
     *
     * @param int $roleId
     * @return Role
     */
    public function edit(int $roleId): Role
    {
        return $this->repository->find($roleId);
    }

    public function update(CreateRoleDTO $createRoleDTO, int $roleId)
    {
        $role = $this->repository->find($roleId);

        $role->syncPermissions($createRoleDTO->permissions);
        $this->repository->update($createRoleDTO, $role);
    }

    /**
     * Удаляет роль при отсутсвии связей
     *
     * @param int $roleId
     * @return void
     */
    public function delete(int $roleId): void
    {
        $role = $this->repository->find($roleId);
        if (isset($role)) {
            $role->syncPermissions([]);
            $this->repository->delete($role); // Добавить проверку
        }

    }
}
