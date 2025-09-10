<?php

declare(strict_types=1);

namespace App\Repositories\Files;

use App\Models\Files\FileTemplate;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;

class FileTemplateRepository implements FileTemplateRepositoryInterface
{
    // public function all(): Collection
    // {
    //     return FileTemplateDTO::collect(FileTemplate::with(['region'])->all());
    // }

    public function find(int $FileTemplateId): FileTemplate
    {
        return FileTemplate::findOrFail($FileTemplateId);
    }

    // public function create(CreateFileTemplateDTO $dto): void
    // {
    //     FileTemplate::create($dto->all());
    // }

    // public function update(int $FileTemplateId, CreateFileTemplateDTO $dto): void
    // {
    //     $FileTemplate = FileTemplate::findOrFail($FileTemplateId);
    //     $FileTemplate->update($dto->all());
    // }

    public function delete(FileTemplate $FileTemplate): void
    {
        $FileTemplate->delete();
    }

    // public function count(): int
    // {
    //     return FileTemplate::count();
    // }

    public function getQuery(): Builder
    {
        return FileTemplate::query();
    }
}
