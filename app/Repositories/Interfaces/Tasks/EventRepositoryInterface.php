<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Tasks;

use App\DTO\Tasks\Event\CreateEventDTO;
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

    /**
     * Создать новое событие
     * @param CreateEventDTO $dto
     * @return void
     */
    public function create(CreateEventDTO $dto): void;
}
