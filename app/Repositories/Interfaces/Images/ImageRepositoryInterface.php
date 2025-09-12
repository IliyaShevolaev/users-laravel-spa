<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Images;

use App\DTO\Image\CreateImageDTO;
use App\Models\Gallery\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ImageRepositoryInterface
{
    //public function all(): Collection;

    //public function find(int $FileTemplateId): FileTemplate;

    public function create(CreateImageDTO $dto): Image;

    // public function update(int $FileTemplateId, CreateFileTemplateDTO $dto): void;

    //public function delete(FileTemplate $FileTemplate): void;
}
