<?php

declare(strict_types=1);

namespace App\DTO\User\Position;

use Spatie\LaravelData\Data;

class PositionDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $createdAt,
        public string $updatedAt
    ) {
    }
}
