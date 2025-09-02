<?php

declare(strict_types=1);

namespace App\DTO\Tasks\Event;

use Spatie\LaravelData\Data;

class MarkEventDTO extends Data
{
    public function __construct(
        public string $endTime,
    ) {
    }
}
