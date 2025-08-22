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


    public function getCurrentVisible(string $start, string $end, int|null $department_id): Collection
    {
        return EventDTO::collect(
            Event::whereDate('start', '<=', $end)
                ->whereDate('end', '>=', $start)
                ->where(function ($query) use ($department_id) {
                    $query->where('all_vision', true)
                        ->orWhere('department_id', $department_id);
                })
                ->addSelect([
                    'is_done' => EventUser::selectRaw('COUNT(*) > 0')
                        ->whereColumn('event_id', 'events.id')
                        ->where('user_id', Auth::id())
                ])
                ->get()
        );
    }

    public function create(CreateEventDTO $dto): void
    {
        $event = Event::create($dto->all());

        $event->users()->attach(
            collect($dto->user_id)->mapWithKeys(fn($id) => [$id => ['is_done' => false]])->toArray()
        );
    }

    public function find(int $eventId): Event
    {
        return Event::with('department')
            ->addSelect([
                'is_done' => EventUser::selectRaw('COUNT(*) > 0')
                    ->whereColumn('event_id', 'events.id')
                    ->where('user_id', Auth::id())
            ])
            ->findOrFail($eventId);
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
