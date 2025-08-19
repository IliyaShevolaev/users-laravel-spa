<?php

namespace App\DTO\Tasks\Event;

use Spatie\LaravelData\Data;

class EventDTO extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $start,
        public string $end,
        public ?int $department_id, // передалать на department
    ) {
    }
}
