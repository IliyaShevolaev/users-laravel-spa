<?php

declare(strict_types=1);

namespace App\Repositories\Cities;

use App\Models\Cities\Region;
use App\DTO\Cities\Region\RegionDTO;
use Illuminate\Database\Eloquent\Builder;
use App\DTO\Cities\Region\CreateRegionDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Cities\RegionRepositoryInterface;

class RegionRepository implements RegionRepositoryInterface
{

    public function all(): Collection
    {
        return RegionDTO::collect(Region::all());
    }

    public function create(CreateRegionDTO $dto): void
    {
        Region::create($dto->all());
    }

    public function update(int $regionId, CreateRegionDTO $dto): void
    {
        $region = Region::findOrFail($regionId);
        $region->update($dto->all());
    }

    public function delete(Region $region): void
    {
        $region->delete();
    }

    public function find(int $regionId): Region
    {
        return Region::findOrFail($regionId);
    }

    public function getQuery(): Builder
    {
        return Region::query();
    }

    public function count(): int
    {
        return Region::count();
    }
}
