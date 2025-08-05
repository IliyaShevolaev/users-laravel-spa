<?php

declare(strict_types=1);

namespace App\Repositories\User\Department;

use App\DTO\User\UserDTO;
use ClassTransformer\Hydrator;
use App\Models\User\Department;
use App\DTO\User\Department\DepartmentDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{

    public function all(): Collection
    {
        return DepartmentDTO::collect(Department::all());
    }

    public function create(DepartmentDTO $dto): void
    {
        Department::create($dto->all());
    }

    public function update(int $department_id, DepartmentDTO $dto): void
    {
        $department = Department::findOrFail($department_id);
        $department->update($dto->all());
    }

    public function delete(int $department_id): void
    {
        $department = Department::findOrFail($department_id);
        $department->delete();
    }

    public function find(int $department_id): DepartmentDTO
    {
        return DepartmentDTO::from(Department::findOrFail($department_id));
    }

    public function findRelatedUsers(int $department_id): Collection
    {
        $users = Department::findOrFail($department_id)->users()->get();

        return UserDTO::collect($users);
    }
}
