<?php

namespace App\DTO\Tasks\Event;

use App\DTO\User\UserDTO;
use App\Models\User\Department;
use Spatie\LaravelData\Data;

class EventDTO extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $start,
        public string $end,
        public ?UserDTO $creator = null,
        public ?bool $isDone = null,
        public ?string $description = null,
    ) {
    }
}
