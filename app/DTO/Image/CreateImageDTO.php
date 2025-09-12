<?php

declare(strict_types=1);

namespace App\DTO\Image;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class CreateImageDTO extends Data
{
    public function __construct(
        public string $name,
        public ?UploadedFile $imageFile,
    ) {
    }
}
