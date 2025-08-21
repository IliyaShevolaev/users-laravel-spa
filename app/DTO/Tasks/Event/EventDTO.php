<?php

namespace App\DTO\Tasks\Event;

use App\Models\User\Department;
use Spatie\LaravelData\Data;

class EventDTO extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $start,
        public string $end,
        public bool $allVision,
        public bool $isDone = false,
        public ?Department $department = null,
    ) {
    }
}
