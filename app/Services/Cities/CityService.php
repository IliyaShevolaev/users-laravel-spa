<?php

declare(strict_types=1);

namespace App\Services\Cities;

use App\DTO\MessageDTO;
use App\DTO\Cities\CreateCityDTO;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;

class CityService
{
    public function __construct(
        private CityRepositoryInterface $repository
    ) {
    }

    public function create(CreateCityDTO $dto): void
    {
        $this->repository->create($dto);
    }

    public function update(int $cityId, CreateCityDTO $dto): void
    {
        $this->repository->update($cityId, $dto);
    }

    public function delete(int $cityId): void
    {
        $city = $this->repository->find($cityId);
        $this->repository->delete($city);
    }
}
