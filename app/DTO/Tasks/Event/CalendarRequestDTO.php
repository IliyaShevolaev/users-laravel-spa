<?php

declare(strict_types=1);

namespace App\DTO\Tasks\Event;

use Spatie\LaravelData\Data;

class CalendarRequestDTO extends Data
{
    public function __construct(
        public string $start,
        public string $end,
    ) {
    }
}
