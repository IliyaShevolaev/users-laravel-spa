<?php

namespace App\DTO\Tasks\Event;

use Spatie\LaravelData\Data;

class CreateEventDTO extends Data
{
    public function __construct(
        public string $title,
        public string $start,
        public string $end,
        public ?int $department_id,
    ) {
    }
}
