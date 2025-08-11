<?php

namespace App\Repositories\Interfaces\User\Position;

use App\DTO\User\UserDTO;
use App\Models\User\Position;
use App\DTO\User\Position\PositionDTO;
use App\DTO\User\Position\CreatePositionDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface PositionRepositoryInterface
{
    /**
     * Получить коллекцию отделов DTO
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Создать запись
     *
     * @param CreatePositionDTO $data
     * @return void
     */
    public function create(CreatePositionDTO $data): void;

    /**
     * Обновить должность
     *
     * @param int $positionId
     * @param CreatePositionDTO $data
     * @return void
     */
    public function update(int $positionId, CreatePositionDTO $data): void;

    /**
     * Удалить должность
     *
     * @param int $positionId
     * @return void
     */
    public function delete(int $positionId): void;

    /**
     * Найти должность по id
     *
     * @param int $positionId
     * @return PositionDTO
     */
    public function find(int $positionId): PositionDTO;

    /**
     * Найти пользователей по должности
     *
     * @param int $positionId
     * @return Collection
     */
    public function findRelatedUsers(int $positionId): Collection;


    /**
     * Возвращает query builder
     *
     * @return Builder<Position>
     */
    public function getQuery(): Builder;


    /**
     * Возвращает количество записей
     *
     * @return int
     */
    public function count(): int;
}
