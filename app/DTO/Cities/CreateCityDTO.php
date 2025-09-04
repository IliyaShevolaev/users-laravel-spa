<?php

declare(strict_types=1);

namespace App\DTO\Cities;

use Spatie\LaravelData\Data;

class CreateCityDTO extends Data
{
    public function __construct(
        public string $name,
        public int $regionId,
        public string $ipStart,
        public string $ipEnd,
    ) {
    }
}
