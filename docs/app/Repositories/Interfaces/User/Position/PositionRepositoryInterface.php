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
     * @param Position $position
     * @return void
     */
    public function delete(Position $position): void;

    /**
     * Найти должность по id
     *
     * @param int $positionId
     * @return Position
     */
    public function find(int $positionId): Position;

    /**
     * Найти пользователей по должности
     *
     * @param Position $position
     * @return Collection
     */
    public function findRelatedUsers(Position $position): Collection;


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
