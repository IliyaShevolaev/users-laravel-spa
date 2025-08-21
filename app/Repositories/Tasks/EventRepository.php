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


    public function getCurrentVisible(string $start, string $end, int|null $department_id): Collection
    {
        return EventDTO::collect(
            Event::with('department')
                ->whereDate('start', '<=', $end)
                ->whereDate('end', '>=', $start)
                ->where(function ($query) use ($department_id) {
                    $query->where('all_vision', true)
                        ->orWhere('department_id', $department_id);
                })
                ->get()
        );
    }

    public function create(CreateEventDTO $dto): void
    {
        debugbar()->info($dto);
        Event::create($dto->all());
    }

    public function find(int $eventId): Event
    {
        return Event::with('department')->findOrFail($eventId);
    }

    public function update(Event $updateEvent, CreateEventDTO $dto)
    {
        $updateEvent->update($dto->all());
    }

    public function delete(Event $event): void
    {
        $event->delete();
    }
}
