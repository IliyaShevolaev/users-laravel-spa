<?php

declare(strict_types=1);

namespace App\Services\Tasks;

use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(private EventRepositoryInterface $repository)
    {
    }

    /**
     * Получить события между датами
     *
     * @param CalendarRequestDTO $calendarRequestDTO
     * @return Collection
     */
    public function getBetween(CalendarRequestDTO $calendarRequestDTO): Collection
    {
        return $this->repository->between($calendarRequestDTO->start, $calendarRequestDTO->end);
    }

    /**
     * Создать событие
     *
     * @param CreateEventDTO $dto
     * @return void
     */
    public function create(CreateEventDTO $dto): void
    {
        $this->repository->create($dto);
    }
}
