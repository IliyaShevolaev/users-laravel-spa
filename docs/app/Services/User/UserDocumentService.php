<?php

declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Str;
use App\Models\User\UserDocument;
use Illuminate\Support\Facades\Storage;
use App\DTO\User\Documents\CreateUserDocumentDTO;
use App\Repositories\Interfaces\User\UserDocumentRepositoryInterface;

class UserDocumentService
{
    public function __construct(private UserDocumentRepositoryInterface $repository)
    {
    }

    public function getFilePath(string $fileName): string
    {
        $path = storage_path("app/private/documents/{$fileName}");

        if (!file_exists($path)) {
            abort(404);
        }

        return $path;
    }

    public function create(CreateUserDocumentDTO $dto): void
    {
        $extension = $dto->file->getClientOriginalExtension();
        $uniqueName = Str::uuid() . '.' . $extension;

        $dto->setFileName($uniqueName);

        $dto->file->storeAs(
            'documents',
            $uniqueName
        );

        $this->repository->create($dto);
    }

    public function delete(UserDocument $userDocument): void
    {
        $pathToFile = 'documents/' . $userDocument->file_name;

        $this->repository->delete($userDocument);

        Storage::delete($pathToFile);
    }
}
