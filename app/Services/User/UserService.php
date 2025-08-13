<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\Roles\RoleDTO;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use App\DTO\User\CreateUserDTO;
use App\DTO\User\UserRelatedDTO;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

/**
 * Сервис для работы с пользователями
 */
class UserService
{
    /**
     * Реаозиторий для представления данных для пользователей
     * @var UserRepositoryInterface
     * Репозиторий для представления данных для отделов
     * @var DepartmentRepositoryInterface
     * Репозиторий для представления данных для должностей
     * @var PositionRepositoryInterface
     * Репозиторий для представления данных для должностей
     * @var RoleRepositoryInterface
     */
    public function __construct(
        private UserRepositoryInterface $repository,
        private DepartmentRepositoryInterface $departmentRepository,
        private PositionRepositoryInterface $positionRepository,
        private RoleRepositoryInterface $roleRepository
    ) {
    }

    /**
     * Создает нового пользователя
     *
     * @param CreateUserDTO $dto
     * @return void
     */
    public function create(CreateUserDTO $dto): void
    {
        $createdUser = $this->repository->create($dto);

        if (isset($dto->role)) {
            $createdUser->addRole($this->roleRepository->find($dto->role));
        }
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param CreateUserDTO $editedData
     * @param int $userId
     * @return void
     */
    public function update(CreateUserDTO $dto, int $userId): void
    {
        $updatedUser = $this->repository->update($userId, $dto);

        if (Auth::user()->getUserRolePermissionsCollection()->contains('roles-update')) {
            if (isset($dto->role)) {
                $updatedUser->syncRoles([$this->roleRepository->find($dto->role)]);
            } else {
                $updatedUser->syncRoles([]);
            }
        }
    }

    /**
     * Удаляет данные о пользователе
     *
     * @param int $userId
     * @return void
     */
    public function delete(int $userId): void
    {
        if (Auth::id() !== $userId) {
            $this->repository->delete($userId);
        }
    }

    /**
     * Подготавливает данные перед отображением формы создания/редактирования пользователя
     *
     * @param int|null $userId
     * @return UserRelatedDTO
     */
    public function prepareViewData(int $userId = null): UserRelatedDTO
    {
        $userRelatedDtoArray = [];

        $userRelatedDtoArray['user'] = isset($userId) ? $this->repository->withoutScopeFind($userId) : null;

        $userRelatedDtoArray['departments'] = $this->departmentRepository->all();
        $userRelatedDtoArray['positions'] = $this->positionRepository->all();

        $permissions = Auth::user()->getUserRolePermissionsCollection();
        if ($permissions->contains('roles-update')) {
            $userRelatedDtoArray['roles'] = $this->roleRepository->all();
        }


        $genderArray = [];
        foreach (GenderEnum::cases() as $genderValue) {
            array_push($genderArray, [
                'text' => trans('main.users.genders.' . $genderValue->value),
                'value' => $genderValue->value
            ]);
        }
        $userRelatedDtoArray['genders'] = $genderArray;

        $statusArray = [];
        foreach (StatusEnum::cases() as $statusValue) {
            array_push($statusArray, [
                'text' => trans('main.users.statuses.' . $statusValue->value),
                'value' => $statusValue->value
            ]);
        }
        $userRelatedDtoArray['statuses'] = $statusArray;


        return UserRelatedDTO::from($userRelatedDtoArray);
    }
}
