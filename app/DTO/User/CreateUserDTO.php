<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use Spatie\LaravelData\Data;

class CreateUserDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,

        public ?int $departmentId = null,
        public ?int $positionId = null,
        public ?int $role = null,

        public GenderEnum $gender,
        public StatusEnum $status
    ) {
    }
}
