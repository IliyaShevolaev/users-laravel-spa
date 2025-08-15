<?php

declare(strict_types=1);

namespace App\DTO\DataTable;

use Spatie\LaravelData\Data;

class DatatableRequestDTO extends Data
{
    public function __construct(
        public ?int $page = null,
        public ?int $perPage = null,
        public ?string $sortBy = null,
        public ?string $sortOrder = null,
        public ?string $search = null,
        public ?int $draw = null,
    ) {
    }
}
