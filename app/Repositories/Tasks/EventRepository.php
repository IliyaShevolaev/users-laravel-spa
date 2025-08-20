<?php

declare(strict_types=1);

namespace App\Repositories\Tasks;

use App\DTO\Tasks\Event\CreateEventDTO;
use App\DTO\Tasks\Event\EventDTO;
use App\Models\Tasks\Event;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function all(): Collection
    {
        return EventDTO::collect(Event::all());
    }

    public function between(string $start, string $end): Collection
    {
        return EventDTO::collect(Event::whereDate('start', '<=', $end)
            ->whereDate('end', '>=', $start)
            ->get());
    }

    public function create(CreateEventDTO $dto): void
    {
        Event::create($dto->all());
    }
}
