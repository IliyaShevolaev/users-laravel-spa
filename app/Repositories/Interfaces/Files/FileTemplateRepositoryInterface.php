<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Files;

use App\Models\Files\FileTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface FileTemplateRepositoryInterface
{
    public function all(): Collection;

    public function find(int $FileTemplateId): FileTemplate;

    // public function create(CreateFileTemplateDTO $dto): void;

    // public function update(int $FileTemplateId, CreateFileTemplateDTO $dto): void;

    public function delete(FileTemplate $FileTemplate): void;

    public function getQuery(): Builder;
}
