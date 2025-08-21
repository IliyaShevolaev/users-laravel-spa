<?php

namespace App\DTO\Tasks\Event;

use App\Models\User\Department;
use Spatie\LaravelData\Data;

class EventUserRelationDTO extends Data
{
    public function __construct(
        public int $eventId,
        public int $userId,
    ) {
    }
}
