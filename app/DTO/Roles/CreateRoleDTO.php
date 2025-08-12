<?php

declare(strict_types=1);

namespace App\DTO\Roles;

use Spatie\LaravelData\Data;

class CreateRoleDTO extends Data
{
    public function __construct(
        public string $name,
        public array $permissions,
    ) {
    }
}
