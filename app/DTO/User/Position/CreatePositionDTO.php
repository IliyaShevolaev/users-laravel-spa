<?php

declare(strict_types=1);

namespace App\DTO\User\Position;

use Spatie\LaravelData\Data;

class CreatePositionDTO extends Data
{
    public function __construct(
        public string $name,
    ) {
    }
}
