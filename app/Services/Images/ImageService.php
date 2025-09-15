<?php

declare(strict_types=1);

namespace App\Services\Images;

use App\Models\Gallery\Image;
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

    public function update(CreateImageDTO $dto, Image $image)
    {
        if (isset($dto->imageFile)) {
            $image->clearMediaCollection('gallery');
            $image->addMedia($dto->imageFile)->toMediaCollection('gallery');
        }

        $this->repository->update($image, $dto);
    }

    public function delete(Image $image)
    {
        $image->clearMediaCollection('gallery');

        $this->repository->delete($image);
    }
}
