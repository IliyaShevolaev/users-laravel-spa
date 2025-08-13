<?php

declare(strict_types=1);

namespace App\Services\Roles;

use App\DTO\MessageDTO;
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
     * Удаляет роль при отсутствии связей
     *
     * @param int $roleId
     * @return MessageDTO
     */
    public function delete(int $roleId): MessageDTO
    {
        $result = [];

        $role = $this->repository->find($roleId);

        if ($role->users()->exists()) {
            $result['message'] = 'delete not allowed';
            $result['code'] = 409;
            return MessageDTO::from($result);
        } else {
            $role->syncPermissions([]);
            $this->repository->delete($role);

            $result['message'] = 'success';
            $result['code'] = 200;
        }


        return MessageDTO::from($result);
    }

}
