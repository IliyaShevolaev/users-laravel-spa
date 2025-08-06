<?php

declare(strict_types=1);

namespace App\DTO\User\Department;

use Spatie\LaravelData\Data;

class DepartmentDTO extends Data
{
    public function __construct(
        public ?string $id = null,
        public string $name,
        public ?string $created_at,
        public ?string $updated_at
    ) {
    }
}
