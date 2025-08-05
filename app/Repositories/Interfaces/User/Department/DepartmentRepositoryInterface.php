<?php

namespace App\Repositories\Interfaces\User\Department;

use App\DTO\User\UserDTO;
use App\Models\User\Department;
use App\DTO\User\Department\DepartmentDTO;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentRepositoryInterface
{
    /**
     * Получить колликцию элементов DTO
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Создать запись
     *
     * @param DepartmentDTO $dto
     * @return void
     */
    public function create(DepartmentDTO $dto): void;

    /**
     * Обновить отдел
     *
     * @param int $department_id
     * @param DepartmentDTO $dto
     * @return void
     */
    public function update(int $department_id, DepartmentDTO $dto): void;

    /**
     * Удалить отдел
     *
     * @param int $department_id
     * @return void
     */
    public function delete(int $department_id): void;

    /**
     * Найти отдел по id
     *
     * @param int $department_id
     * @return DepartmentDTO
     */
    public function find(int $department_id): DepartmentDTO;

    /**
     * Найти пользователей по отделу
     *
     * @param int $department_id
     * @return Collection
     */
    public function findRelatedUsers(int $department_id): Collection;
}
