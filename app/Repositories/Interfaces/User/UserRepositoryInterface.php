<?php

namespace App\Repositories\Interfaces\User;

use App\DTO\User\CreateUserDTO;
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
     * @param CreateUserDTO $dto
     * @return User
     */
    public function create(CreateUserDTO $dto): User;

    /**
     * Обновить должность
     *
     * @param int $userId
     * @param CreateUserDTO $dto
     * @return User
     */
    public function update(int $userId, CreateUserDTO $dto): User;

    /**
     * Удалить должность
     *
     * @param int $userId
     * @return void
     */
    public function delete(int $userId): void;

    /**
     * Найти должность по id
     *
     * @param int $userId
     * @return User
     */
    public function find(int $userId): User;


    /**
     * Поиск без scope
     *
     * @param int $userId
     * @return UserDTO
     */
    public function withoutScopeFind(int $userId): UserDTO;

    /**
     * Получить Builder с отношениями и без scope
     *
     * @param array $relations
     * @return Builder<User>
     */
    public function getQueryWithRelations(array $relations): Builder;

    /**
     * Получить количество записей
     *
     * @return int
     */
    public function count(): int;

    public function getRelatedRole(int $userId);
}
