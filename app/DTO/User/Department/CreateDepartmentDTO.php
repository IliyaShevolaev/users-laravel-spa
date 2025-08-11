<?php

declare(strict_types=1);

namespace App\DTO\User\Department;

use Spatie\LaravelData\Data;

class CreateDepartmentDTO extends Data
{
    public function __construct(
        public string $name,
    ) {
    }
}
