<?php

declare(strict_types=1);

namespace App\DTO\Roles;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class CreateRoleDTO extends Data
{
    public function __construct(
        public string $name,
        public string $displayName,
        public array $permissions,
    ) {
    }

    /**
     * Задать уникальное имя в Slug формате
     *
     * @return void
     */
    protected function setName(): void
    {
        $this->name = Str::slug($this->displayName);
    }
}
