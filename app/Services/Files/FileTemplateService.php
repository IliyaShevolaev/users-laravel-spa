<?php

declare(strict_types=1);

namespace App\Services\Files;

use App\DTO\Files\Templates\CreateFileTemplateDTO;
use App\Models\Files\FileTemplate;

class FileTemplateService
{
    public function create(CreateFileTemplateDTO $dto): void
    {
        $filePath = $dto->fileTemplate->store('templates');

        $dto->setFilePath($filePath);
        FileTemplate::create($dto->all());
    }
}
