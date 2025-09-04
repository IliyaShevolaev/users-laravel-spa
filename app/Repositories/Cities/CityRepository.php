<?php

declare(strict_types=1);

namespace App\Repositories\Cities;

use App\DTO\Cities\CityDTO;
use App\Models\Cities\City;
use App\DTO\Cities\CreateCityDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;

class CityRepository implements CityRepositoryInterface
{
    public function all(): Collection
    {
        return CityDTO::collect(City::with(['region'])->all());
    }

    public function find(int $cityId): City
    {
        return City::with('region')->findOrFail($cityId);
    }

    public function create(CreateCityDTO $dto): void
    {
        City::create($dto->all());
    }

    public function update(int $cityId, CreateCityDTO $dto): void
    {
        $city = City::findOrFail($cityId);
        $city->update($dto->all());
    }

    public function delete(City $city): void
    {
        $city->delete();
    }

    public function getQuery(): Builder
    {
        return City::with('region');
    }

    public function count(): int
    {
        return City::count();
    }
}
