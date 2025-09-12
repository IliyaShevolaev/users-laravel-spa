<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\DTO\Image\CreateImageDTO;
use App\Models\Gallery\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Images\ImageRepositoryInterface;

class ImageRepository implements ImageRepositoryInterface
{
    //public function all(): Collection;

    //public function find(int $FileTemplateId): FileTemplate;

    public function create(CreateImageDTO $dto): Image
    {
        $image = Image::create($dto->all());

        return $image;
    }

    // public function update(int $FileTemplateId, CreateFileTemplateDTO $dto): void;

    //public function delete(FileTemplate $FileTemplate): void;
}
