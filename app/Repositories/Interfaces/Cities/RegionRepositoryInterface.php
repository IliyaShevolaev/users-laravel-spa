<?php

namespace App\Repositories\Interfaces\Cities;

use App\Models\Cities\Region;
use App\DTO\Cities\Region\RegionDTO;
use Illuminate\Database\Eloquent\Builder;
use App\DTO\Cities\Region\CreateRegionDTO;
use Illuminate\Database\Eloquent\Collection;

interface RegionRepositoryInterface
{
    public function all(): Collection;

    public function create(CreateRegionDTO $dto): void;

    public function update(int $regionId, CreateRegionDTO $dto): void;

    public function delete(Region $region): void;

    public function find(int $regionId): Region;

    public function getQuery(): Builder;

    public function count(): int;
}
