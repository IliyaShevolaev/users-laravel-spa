<?php

declare(strict_types=1);

namespace App\Services\Cities;

use App\DTO\MessageDTO;
use App\DTO\Cities\Region\CreateRegionDTO;
use App\Repositories\Interfaces\Cities\RegionRepositoryInterface;

class RegionService
{
    public function __construct(
        private RegionRepositoryInterface $repository
    ) {
    }

    public function create(CreateRegionDTO $dto): void
    {
        $this->repository->create($dto);
    }

    public function update(int $regionId, CreateRegionDTO $dto): void
    {
        $this->repository->update($regionId, $dto);
    }

    public function delete(int $regionId): MessageDTO
    {
        $result = collect();
        $regionToDelete = $this->repository->find($regionId);

        if ($regionToDelete->cities()->doesntExist()) {
            $this->repository->delete($regionToDelete);

            $result->put('message', 'success');
            $result->put('code', 200);
        } else {
            $result->put('message', 'delete not allowed');
            $result->put('code', 409);
        }

        return MessageDTO::from($result);
    }
}
