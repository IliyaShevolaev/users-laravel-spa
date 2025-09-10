<?php

declare(strict_types=1);

namespace App\DTO\User;

use Spatie\LaravelData\Data;

class ExportUserDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $gender,
        public string $status,
        public string $department,
        public string $position,
        public string $role,
        public string $created_at,
        public string $updated_at
    ) {
    }
}
