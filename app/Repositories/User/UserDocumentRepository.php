<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\UserDocument;
use App\Models\Files\FileTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\User\Documents\CreateUserDocumentDTO;
use App\Repositories\Interfaces\User\UserDocumentRepositoryInterface;

class UserDocumentRepository implements UserDocumentRepositoryInterface
{
    public function all(): Collection
    {
        return UserDocument::all();
    }

    public function find(int $documentId): UserDocument
    {
        return UserDocument::find($documentId);
    }

    public function create(CreateUserDocumentDTO $dto): void
    {
        UserDocument::create($dto->all());
    }

    public function delete(UserDocument $userDocument): void
    {
        $userDocument->delete();
    }


    public function getQuery(): Builder
    {
        return UserDocument::query();
    }
}
