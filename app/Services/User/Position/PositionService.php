<?php

declare(strict_types=1);

namespace App\Services\User\Position;

use App\DTO\MessageDTO;
use App\DTO\User\Position\PositionDTO;
use App\Models\User\Position;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;
use ClassTransformer\Hydrator;

/**
 * Сервис для работы с должностями пользователей
 */
class PositionService
{

    /**
     * Реаозиторий для представления данных для должностей
     *
     * @var PositionRepositoryInterface
     */
    public function __construct(private PositionRepositoryInterface $repository)
    {
    }

    /**
     * Создать должность
     *
     * @param PositionDTO $dto
     * @return void
     */
    public function create(PositionDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Обновить должность
     *
     * @param int $position_id
     * @param PositionDTO $dto
     * @return void
     */
    public function update(int $position_id, PositionDTO $dto): void
    {
        $this->repository->update($position_id, $dto);
    }

    /**
     *  Удалить должость
     *
     * @param int $position_id
     * @return MessageDTO
     */
    public function delete(int $position_id): MessageDTO
    {
        $result = [];

        if ($this->repository->findRelatedUsers($position_id)->isEmpty()) {
            $this->repository->delete($position_id);

            $result['message'] = 'success';
            $result['code'] = 200;
        } else {
            $result['message'] = 'delete not allowed';
            $result['code'] = 409;
        }

        return MessageDTO::from($result);
    }
}
