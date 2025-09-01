<?php

namespace App\DTO\Tasks\Stats;

use Spatie\LaravelData\Data;

class StatsChartDTO extends Data
{
    public function __construct(
        /** @var string[] */
        public array $categories,

        /** @var int[] */
        public array $data,
    ) {
    }
}
