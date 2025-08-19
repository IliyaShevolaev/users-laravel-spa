<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Tasks;

use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    /**
     * Получить все события
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Получить события в промежутке
     *
     * @param string $start
     * @param string $end
     * @return Collection
     */
    public function between(string $start, string $end): Collection;
}
