<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Tasks;

use App\Models\Tasks\Event;
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
     * Получить текущие видимые события
     *
     * @param string $start
     * @param string $end
     * @param int|null $department_id
     * @return Collection
     */
    public function getCurrentVisible(string $start, string $end, int|null $department_id): Collection;

    /**
     * Создать новое событие
     * @param CreateEventDTO $dto
     * @return void
     */
    public function create(CreateEventDTO $dto): void;

    /**
     * Найти событие по id
     *
     * @param int $eventId
     * @return Event
     */
    public function find(int $eventId): Event;
}
