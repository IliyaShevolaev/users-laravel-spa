<?php

declare(strict_types=1);

namespace App\Repositories\User\Position;

use App\DTO\User\UserDTO;
use App\Models\User\Position;
use App\DTO\User\Position\PositionDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

class PositionRepository implements PositionRepositoryInterface
{

    public function all(): Collection
    {
        return PositionDTO::collect(Position::all());
    }

    public function create(PositionDTO $dto): void
    {
        Position::create($dto->all());
    }

    public function update(int $position_id, PositionDTO $dto): void
    {
        $position = Position::findOrFail($position_id);
        $position->update($dto->all());
    }

    public function delete(int $position_id): void
    {
        $position = Position::findOrFail($position_id);
        $position->delete();
    }

    public function find(int $position_id): PositionDTO
    {
        return PositionDTO::from(Position::findOrFail($position_id));
    }

    public function findRelatedUsers(int $position_id): Collection
    {
        $users = Position::findOrFail($position_id)->users()->get();

        return UserDTO::collect($users);
    }
}
