<?php

declare(strict_types=1);

namespace App\Repositories\Tasks;

use App\Models\User;
use App\Models\Tasks\Event;
use App\Models\Tasks\EventUser;
use App\DTO\Tasks\Event\EventDTO;
use Illuminate\Support\Facades\Auth;
use App\DTO\Tasks\Event\PatchEventDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\Tasks\Event\EventUserRelationDTO;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;

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


    public function getCurrentVisible(string $start, string $end): Collection
    {
        $events = Auth::user()->events()
            ->whereDate('start', '<=', $end)
            ->whereDate('end', '>=', $start)
            ->get();

        return EventDTO::collect($events);
    }

    public function create(CreateEventDTO $dto): void
    {
        $event = Event::create($dto->all());

        $event->users()->attach($dto->userId);
    }

    public function find(int $eventId): Event
    {
        $event = Event::with('creator')->findOrFail($eventId);

        return $event;
    }


    public function findModel(int $eventId): Event
    {
        return Event::findOrFail($eventId);
    }

    public function update(Event $updateEvent, PatchEventDTO $dto)
    {
        $updateEvent->update(collect($dto->all())->reject(fn($value) => is_null($value))->toArray());
    }

    public function delete(Event $event): void
    {
        $event->delete();
    }

    public function makeEventUserRelation(EventUserRelationDTO $dto): void
    {
        EventUser::create($dto->all());
    }

    public function deleteEventUserRelation(EventUserRelationDTO $dto): void
    {
        $relationToDelete = EventUser::where('user_id', $dto->userId)->where('event_id', $dto->eventId);
        $relationToDelete->delete();
    }

    public function getUserCompletedEvents(User $user): Collection
    {
        return EventDTO::collect($user->completedEvents()->get());
    }

    public function checkRelation(EventUserRelationDTO $dto)
    {
        return EventUser::where('user_id', $dto->userId)->where('event_id', $dto->eventId)->exists();
    }
}
