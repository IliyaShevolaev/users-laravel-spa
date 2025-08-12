<?php

declare(strict_types=1);

namespace App\Services\Roles;

use App\DTO\Roles\CreateRoleDTO;
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
        $this->repository->create($createRoleDTO);
    }
}
