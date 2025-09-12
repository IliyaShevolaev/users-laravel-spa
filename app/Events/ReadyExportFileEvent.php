<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReadyExportFileEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $userId,
        public string $fileName,
        public ?string $downloadName = null)
    {
        if (!$this->downloadName) {
            $this->downloadName = $this->fileName;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("export.file.ready.{$this->userId}"),
        ];
    }

    public function broadcastAs()
    {
        return 'export.file.ready';
    }

    public function broadcastWith(): array
    {
        return [
            'userId' => $this->userId,
            'fileName' => $this->fileName,
            'downloadName' => $this->downloadName
        ];
    }
}
