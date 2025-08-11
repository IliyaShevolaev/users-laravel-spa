<?php

declare(strict_types=1);

namespace App\Repositories\User\Department;

use App\DTO\User\UserDTO;
use App\Models\User\Department;
use App\DTO\User\Department\DepartmentDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\User\Department\CreateDepartmentDTO;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{

    public function all(): Collection
    {
        return DepartmentDTO::collect(Department::all());
    }

    public function create(CreateDepartmentDTO $dto): void
    {
        Department::create($dto->all());
    }

    public function update(int $departmentId, CreateDepartmentDTO $dto): void
    {
        $department = Department::findOrFail($departmentId);
        $department->update($dto->all());
    }

    public function delete(int $departmentId): void
    {
        $department = Department::findOrFail($departmentId);
        $department->delete();
    }

    public function find(int $departmentId): DepartmentDTO
    {
        return DepartmentDTO::from(Department::findOrFail($departmentId));
    }

    public function findRelatedUsers(int $departmentId): Collection
    {
        $users = Department::findOrFail($departmentId)->users()->get();

        return UserDTO::collect($users);
    }

    public function getQuery(): Builder
    {
        return Department::query();
    }


    public function count(): int
    {
        return Department::count();
    }
}
