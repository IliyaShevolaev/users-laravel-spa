<?php

declare(strict_types=1);

namespace App\DTO\Roles;

use Spatie\LaravelData\Data;

class RoleDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $display_name,
        public bool $system = false,
    ) {
    }
}
