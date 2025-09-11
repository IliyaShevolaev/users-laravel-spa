<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\User;

use App\Models\User\UserDocument;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\User\Documents\CreateUserDocumentDTO;

interface UserDocumentRepositoryInterface
{
    public function all(): Collection;

    public function create(CreateUserDocumentDTO $dto): void;

    public function find(int $documentId): UserDocument;

    public function delete(UserDocument $userDocument): void;
}
