<?php

declare(strict_types=1);

namespace App\Services\User\Position;

use App\DTO\MessageDTO;
use App\Models\User\Position;
use ClassTransformer\Hydrator;
use App\DTO\User\Position\PositionDTO;
use App\DTO\User\Position\CreatePositionDTO;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

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
     * @param CreatePositionDTO $dto
     * @return void
     */
    public function create(CreatePositionDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Обновить должность
     *
     * @param int $positionId
     * @param CreatePositionDTO $dto
     * @return void
     */
    public function update(int $positionId, CreatePositionDTO $dto): void
    {
        $this->repository->update($positionId, $dto);
    }

    /**
     *  Удалить должость
     *
     * @param int $positionId
     * @return MessageDTO
     */
    public function delete(int $positionId): MessageDTO
    {
        $result = collect();
        $positionToDelete = $this->repository->find($positionId);

        if ($this->repository->findRelatedUsers($positionToDelete)->isEmpty()) {
            $this->repository->delete($positionToDelete);

            $result->put('message', 'success');
            $result->put('code', 200);
        } else {
            $result->put('message', 'delete not allowed');
            $result->put('code', 409);
        }

        return MessageDTO::from($result);
    }
}
