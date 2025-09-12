<?php

declare(strict_types=1);

namespace App\DTO\User\Documents;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class CreateUserDocumentDTO extends Data
{
    public string $name;
    public string $fileName;

    public function __construct(
        public int $user_id,
        public ?UploadedFile $file,
    ) {
    }

    public function setFileName($uniqueName): void
    {
        $this->name = $this->file->getClientOriginalName();
        $this->fileName = $uniqueName;
    }
}
