<?php

declare(strict_types=1);

namespace App\Services\Roles;

use App\DTO\MessageDTO;
use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use App\Enums\Role\SystemRolesEnum;
use Illuminate\Support\Facades\Log;
use App\Events\ChangeRolePermissions;
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
            $createdRole->givePermissions($createRoleDTO->permissions);
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
        return $this->repository->findWithPermissions($roleId);
    }

    /**
     * Обновить роль
     *
     * @param CreateRoleDTO $createRoleDTO
     * @param Role $role
     * @return void
     */
    public function update(CreateRoleDTO $createRoleDTO, Role $role)
    {
        $this->repository->update($createRoleDTO, $role);
        $role->syncPermissions($createRoleDTO->permissions);

        broadcast(new ChangeRolePermissions($role->id));
    }

    /**
     * Удаляет роль при отсутствии связей
     *
     * @param Role $role
     * @return MessageDTO
     */
    public function delete(Role $role): MessageDTO
    {
        $result = collect();

        if ($this->repository->findRelatedUsers($role)->isNotEmpty()) {
            $result->put('message', 'delete not allowed');
            $result->put('code', 409);
        } else {
            $this->repository->delete($role);

            $result->put('message', 'success');
            $result->put('code', 200);
        }


        return MessageDTO::from($result);
    }

}
