<?php

namespace App\Repositories\Interfaces\User\Department;

use App\Models\User\Department;
use App\DTO\User\Department\DepartmentDTO;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\User\Department\CreateDepartmentDTO;
use Illuminate\Database\Eloquent\Builder;

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
     * @param CreateDepartmentDTO $dto
     * @return void
     */
    public function create(CreateDepartmentDTO $dto): void;

    /**
     * Обновить отдел
     *
     * @param int $departmentId
     * @param CreateDepartmentDTO $dto
     * @return void
     */
    public function update(int $departmentId, CreateDepartmentDTO $dto): void;

    /**
     * Удалить отдел
     *
     * @param int $departmentId
     * @return void
     */
    public function delete(int $departmentId): void;

    /**
     * Найти отдел по id
     *
     * @param int $departmentId
     * @return DepartmentDTO
     */
    public function find(int $departmentId): DepartmentDTO;

    /**
     * Найти пользователей по отделу
     *
     * @param int $departmentId
     * @return Collection
     */
    public function findRelatedUsers(int $departmentId): Collection;

    /**
     * Возвращает query builder
     *
     * @return Builder<Department>
     */
    public function getQuery(): Builder;


    /**
     * Возвращает количество записей
     *
     * @return int
     */
    public function count(): int;
}
