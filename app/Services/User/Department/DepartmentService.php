<?php

declare(strict_types=1);

namespace App\Services\User\Department;

use App\DTO\MessageDTO;
use ClassTransformer\Hydrator;
use App\Models\User\Department;
use App\DTO\User\Department\DepartmentDTO;
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
     * @param DepartmentDTO $dto
     * @return void
     */
    public function create(DepartmentDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Обновить отдел
     *
     * @param int $department_id
     * @param DepartmentDTO $dto
     * @return void
     */
    public function update(int $department_id, DepartmentDTO $dto): void
    {
        $this->repository->update($department_id, $dto);
    }

    /**
     *  Удалить отдел
     *
     * @param int $department_id
     * @return MessageDTO
     */
    public function delete(int $department_id): MessageDTO
    {
        $result = [];

        if ($this->repository->findRelatedUsers($department_id)->isEmpty()) {
            $this->repository->delete($department_id);

            $result['message'] = 'success';
            $result['code'] = 200;
        } else {
            $result['message'] = 'delete not allowed';
            $result['code'] = 409;
        }

        return MessageDTO::from($result);
    }
}
