<?php

declare(strict_types=1);

namespace App\Repositories\User\Position;

use App\DTO\User\UserDTO;
use App\Models\User\Position;
use App\DTO\User\Position\PositionDTO;
use App\DTO\User\Position\CreatePositionDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

class PositionRepository implements PositionRepositoryInterface
{

    public function all(): Collection
    {
        return PositionDTO::collect(Position::all());
    }

    public function create(CreatePositionDTO $dto): void
    {
        Position::create($dto->all());
    }

    public function update(int $positionId, CreatePositionDTO $dto): void
    {
        $position = Position::findOrFail($positionId);
        $position->update($dto->all());
    }

    public function delete(int $positionId): void
    {
        $position = Position::findOrFail($positionId);
        $position->delete();
    }

    public function find(int $positionId): PositionDTO
    {
        return PositionDTO::from(Position::findOrFail($positionId));
    }

    public function findRelatedUsers(int $positionId): Collection
    {
        $users = Position::findOrFail($positionId)->users()->get();

        return UserDTO::collect($users);
    }

    public function getQuery(): Builder
    {
        return Position::query();
    }


    public function count(): int
    {
        return Position::count();
    }
}
