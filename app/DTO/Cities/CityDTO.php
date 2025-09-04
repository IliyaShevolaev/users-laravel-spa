<?php

declare(strict_types=1);

namespace App\DTO\Cities;

use App\DTO\Cities\Region\RegionDTO;
use Spatie\LaravelData\Data;

class CityDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $startIp,
        public string $endIp,
        public RegionDTO $region,
    ) {
    }
}
