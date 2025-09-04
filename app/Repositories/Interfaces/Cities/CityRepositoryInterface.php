<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Cities;

use App\Models\Cities\City;
use App\DTO\Cities\CreateCityDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface CityRepositoryInterface
{
    public function all(): Collection;

    public function find(int $cityId): City;

    public function create(CreateCityDTO $dto): void;

    public function update(int $cityId, CreateCityDTO $dto): void;

    public function delete(City $city): void;

    public function getQuery(): Builder;

    public function count(): int;
}
