<?php

declare(strict_types=1);

namespace App\Services\User\Department;

use App\DTO\MessageDTO;
use App\DTO\User\Department\CreateDepartmentDTO;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

/**
 * Сервис для работы с отделами пользователей
 */
class DepartmentService
{
    /**
     * Репозиторий для представления данных для отделов
     *
     * @var DepartmentRepositoryInterface
     */
    public function __construct(private DepartmentRepositoryInterface $repository)
    {
    }

    /**
     * Создать отдел
     *
     * @param CreateDepartmentDTO $dto
     * @return void
     */
    public function create(CreateDepartmentDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Обновить отдел
     *
     * @param int $departmentId
     * @param CreateDepartmentDTO $dto
     * @return void
     */
    public function update(int $departmentId, CreateDepartmentDTO $dto): void
    {
        $this->repository->update($departmentId, $dto);
    }

    /**
     *  Удалить отдел
     *
     * @param int $departmentId
     * @return MessageDTO
     */
    public function delete(int $departmentId): MessageDTO
    {
        $result = [];

        if ($this->repository->findRelatedUsers($departmentId)->isEmpty()) {
            $this->repository->delete($departmentId);

            $result['message'] = 'success';
            $result['code'] = 200;
        } else {
            $result['message'] = 'delete not allowed';
            $result['code'] = 409;
        }

        return MessageDTO::from($result);
    }
}
