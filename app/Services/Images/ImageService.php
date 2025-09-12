<?php

declare(strict_types=1);

namespace App\Services\Images;

use App\DTO\Image\CreateImageDTO;
use App\Repositories\Interfaces\Images\ImageRepositoryInterface;

class ImageService
{
    public function __construct(private ImageRepositoryInterface $repository)
    {
    }

    public function create(CreateImageDTO $dto): void
    {
        $image = $this->repository->create($dto);

        $image->addMedia($dto->imageFile)->toMediaCollection('gallery');
    }
}
