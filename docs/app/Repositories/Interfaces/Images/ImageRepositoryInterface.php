<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Images;

use App\DTO\Image\CreateImageDTO;
use App\Models\Gallery\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ImageRepositoryInterface
{
    public function all(): Collection;

    public function find(int $FileTemplateId): Image;

    public function create(CreateImageDTO $dto): Image;

    public function update(Image $image, CreateImageDTO $dto): void;

    public function delete(Image $image): void;
}
