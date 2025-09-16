<?php

namespace App\Events;

use App\Models\Tasks\Event;
use App\DTO\Tasks\Event\EventDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\Tasks\EventResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\Tasks\EventNotifyResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChangeCalendarEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $userId, public EventDTO $event, public bool $isNewAssign)
    {
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("change.calendar.events.{$this->userId}"),
        ];
    }

    public function broadcastAs()
    {
        return 'change.calendar.events';
    }

    public function broadcastWith(): array
    {
        return [
            'userId' => $this->userId,
            'event' => new EventNotifyResource($this->event),
            'isNewAssign' => $this->isNewAssign
        ];
    }
}
