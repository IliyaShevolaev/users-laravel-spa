<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\MessageDTO;
use App\DTO\Roles\RoleDTO;
use App\DTO\User\UserDTO;
use App\Events\ChangeUseRole;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use App\Events\ChangeUserRole;
use App\DTO\User\CreateUserDTO;
use App\DTO\User\UserRelatedDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
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

    public function edit(int $userId): UserDTO
    {
        return $this->repository->withoutScopeFind($userId);
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
            broadcast(new ChangeUserRole($updatedUser->id));
        }
    }

    /**
     * Удаляет данные о пользователе
     *
     * @param int $userId
     * @return MessageDTO
     */
    public function delete(int $userId): MessageDTO
    {
        $result = collect();

        if (Auth::id() !== $userId) {
            $this->repository->delete($userId);

            $result->put('code', 200);
        } else {
            $result->put('code', 409);
        }

        return MessageDTO::from($result);
    }

    /**
     * Подготавливает данные перед отображением формы создания/редактирования пользователя
     *
     * @param int|null $userId
     * @return UserRelatedDTO
     */
    public function prepareViewData(): UserRelatedDTO
    {
        $userRelatedDtoArray = collect();

        // $userRelatedDtoArray->put('user', isset($userId) ? $this->repository->withoutScopeFind($userId) : null);

        $userRelatedDtoArray->put('departments', $this->departmentRepository->all());
        $userRelatedDtoArray->put('positions', $this->positionRepository->all());

        $permissions = Auth::user()->getUserRolePermissionsCollection();
        if ($permissions->contains('roles-update')) {
            $userRelatedDtoArray->put('roles', $this->roleRepository->all());
        }


        $genderCollection = collect();
        foreach (GenderEnum::cases() as $genderValue) {
            $genderCollection->push([
                'text' => trans('main.users.genders.' . $genderValue->value),
                'value' => $genderValue->value,
            ]);
        }
        $userRelatedDtoArray->put('genders', $genderCollection);


        $statusCollection = collect();
        foreach (StatusEnum::cases() as $statusValue) {
            $statusCollection->push([
                'text' => trans('main.users.statuses.' . $statusValue->value),
                'value' => $statusValue->value,
            ]);
        }
        $userRelatedDtoArray->put('statuses', $statusCollection);

        return UserRelatedDTO::from($userRelatedDtoArray);
    }
}
