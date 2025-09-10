<?php

declare(strict_types=1);

namespace App\DTO\Files\Templates;

use App\DTO\Cities\Region\RegionDTO;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateFileTemplateDTO extends Data
{
    public string $filePath;

    public function __construct(
        public string $name,
        public ?UploadedFile $fileTemplate,
    ) {
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
        $this->fileTemplate = null;
    }
}
