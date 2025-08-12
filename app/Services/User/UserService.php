<?php

declare(strict_types=1);

namespace App\Services\User;

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
        $createdUser->addRole($this->roleRepository->find($dto->role));
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
        $updatedUser->addRole($this->roleRepository->find($dto->role));
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
        $userDto = isset($userId) ? $this->repository->withoutScopeFind($userId) : null;

        $departmentsDto = $this->departmentRepository->all();
        $positionsDto = $this->positionRepository->all();
        $rolesDto = $this->roleRepository->all();

        $genderArray = [];
        foreach (GenderEnum::cases() as $genderValue) {
            array_push($genderArray, [
                'text' => trans('main.users.genders.' . $genderValue->value),
                'value' => $genderValue->value
            ]);
        }

        $statusArray = [];
        foreach (StatusEnum::cases() as $statusValue) {
            array_push($statusArray, [
                'text' => trans('main.users.statuses.' . $statusValue->value),
                'value' => $statusValue->value
            ]);
        }

        return UserRelatedDTO::from([
            'user' => $userDto,
            'departments' => $departmentsDto,
            'positions' => $positionsDto,
            'roles' => $rolesDto,
            'genders' => $genderArray,
            'statuses' => $statusArray
        ]);
    }
}
