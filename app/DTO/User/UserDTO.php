<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\DTO\Roles\RoleDTO;
use Spatie\LaravelData\Data;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;

class UserDTO extends Data
{
    public function __construct(
        public int $id,

        public string $name,
        public string $email,

        public ?int $departmentId = null,
        public ?int $positionId = null,

        public GenderEnum $gender,
        public StatusEnum $status
    ) {
    }
}
