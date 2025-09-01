<?php

namespace App\DTO\Tasks\Stats;

use Spatie\LaravelData\Data;

class RequestStatsDTO extends Data
{
    public function __construct(
        public string $start,
        public string $end,
        public ?int $userId = null,
    ) {
    }
}
