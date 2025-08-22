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
        public ?bool $allVision,
        public ?int $departmentId,
    ) {
    }
}
