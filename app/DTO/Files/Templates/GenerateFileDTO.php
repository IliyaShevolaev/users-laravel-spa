<?php

declare(strict_types=1);

namespace App\DTO\Files\Templates;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class GenerateFileDTO extends Data
{
    public string $filePath;

    public function __construct(
        public int $templateId,
        public int $userId,
        public string $format,
    ) {
    }
}
