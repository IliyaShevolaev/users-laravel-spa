<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\DTO\User\UserRelatedDTO;
use ClassTransformer\Hydrator;
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
     * @param UserDTO $dto
     * @return void
     */
    public function create(UserDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param UserDTO $editedData
     * @param int $user_id
     * @return void
     */
    public function update(UserDTO $dto, int $user_id): void
    {
        $this->repository->update($user_id, $dto);
    }

    /**
     * Удаляет данные о пользователе
     *
     * @param int $user_id
     * @return void
     */
    public function delete(int $user_id): void
    {
        if (Auth::id() !== $user_id) {
            $this->repository->delete($user_id);
        }
    }

    /**
     * Подготавливает данные перед отображением формы создания/редактирования пользователя
     *
     * @param int|null $user_id
     * @return UserRelatedDTO
     */
    public function prepareViewData(int $user_id = null): UserRelatedDTO
    {
        $userDto = isset($user_id) ? $this->repository->withoutScopeFind($user_id) : null;

        $departmentsDto = $this->departmentRepository->all();
        $positionsDto = $this->positionRepository->all();

        return UserRelatedDTO::from([
            'userDTO' => $userDto,
            'departments' => $departmentsDto,
            'positions' => $positionsDto,
        ]);
    }
}
