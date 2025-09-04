<?php

declare(strict_types=1);

namespace App\DTO\Cities\Region;

use Spatie\LaravelData\Data;

class RegionDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $createdAt,
        public string $updatedAt
    ) {
    }
}
