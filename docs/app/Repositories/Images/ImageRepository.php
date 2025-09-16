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
    public function all(): Collection
    {
        return Image::orderBy('id', 'asc')->get();
    }

    public function find(int $id): Image
    {
        return Image::find($id);
    }

    public function create(CreateImageDTO $dto): Image
    {
        $image = Image::create($dto->all());

        return $image;
    }

    public function update(Image $image, CreateImageDTO $dto): void
    {
        $image->update($dto->all());
    }

    public function delete(Image $image): void
    {
        $image->delete();
    }
}
