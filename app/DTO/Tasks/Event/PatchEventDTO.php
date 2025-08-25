<?php

declare(strict_types=1);

namespace App\DTO\Tasks\Event;

use Spatie\LaravelData\Data;

class PatchEventDTO extends Data
{
    public function __construct(
        public string $start,
        public string $end,
        public ?string $title,
        public ?string $description,
        public ?array $user_id,
        public ?int $creatorId,
        public ?array $userId,
    ) {
    }
}
