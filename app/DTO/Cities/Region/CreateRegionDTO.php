<?php

declare(strict_types=1);

namespace App\DTO\Cities\Region;

use Spatie\LaravelData\Data;

class CreateRegionDTO extends Data
{
    public function __construct(
        public string $name
    ) {
    }
}
