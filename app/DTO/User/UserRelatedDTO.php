<?php

namespace App\DTO\User;

use Spatie\LaravelData\Data;

class UserRelatedDTO extends Data
{
    public function __construct(
        public ?UserDTO $user = null,

        /** @var \App\DTO\User\Department\DepartmentDTO[] */
        public array $departments,

        /** @var \App\DTO\User\Position\PositionDTO[] */
        public array $positions,

        /** @var \App\DTO\Roles\RoleDTO[] */
        public ?array $roles = null,

        public array $genders,

        public array $statuses,
    ) {
    }
}
