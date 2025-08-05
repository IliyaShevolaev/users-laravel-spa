<?php

namespace App\Repositories\Interfaces\User;

use App\Models\User;
use App\DTO\User\UserDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Получить все записи об должностях
     *
     * @return Collection<int, User>
     */
    public function all(): Collection;

    /**
     * Получить все записи включая неактивные
     *
     * @return Collection<int, User>
     */
    public function allWithUnactive(): Collection;

    /**
     * Создать запись
     *
     * @param UserDTO $dto
     * @return User
     */
    public function create(UserDTO $dto): User;

    /**
     * Обновить должность
     *
     * @param int $user_id
     * @param UserDTO $dto
     * @return void
     */
    public function update(int $user_id, UserDTO $dto): void;

    /**
     * Удалить должность
     *
     * @param int $user_id
     * @return void
     */
    public function delete(int $user_id): void;

    /**
     * Найти должность по id
     *
     * @param int $user_id
     * @return User
     */
    public function find(int $user_id): User;


    /**
     * Поиск без scope
     *
     * @param int $user_id
     * @return UserDTO
     */
    public function withoutScopeFind(int $user_id): UserDTO;
}
