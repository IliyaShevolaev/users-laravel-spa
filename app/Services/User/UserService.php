<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use ClassTransformer\Hydrator;
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
     */
    public function __construct(
        private UserRepositoryInterface $repository,
        private DepartmentRepositoryInterface $departmentRepository,
        private PositionRepositoryInterface $positionRepository
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
        $this->repository->create($dto);
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
        $this->repository->update($userId, $dto);
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
            'genders' => $genderArray,
            'statuses' => $statusArray
        ]);
    }
}
