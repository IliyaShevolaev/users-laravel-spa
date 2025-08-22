<?php

declare(strict_types=1);

namespace App\Enums\Role;

/**
 * Enum системной роли
 */
enum SystemRolesEnum : string
{
    case System = 'system';
    case Admin = 'admin';
    case Manager = 'manager';
    case User = 'user';
}
