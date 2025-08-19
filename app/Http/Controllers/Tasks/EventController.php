<?php

namespace App\Http\Controllers\Tasks;

use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\EventRequest;
use App\Http\Resources\Tasks\EventResource;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Services\Tasks\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private EventService $service
    ) {
    }

    public function index(EventRequest $eventRequest)
    {
        $requestedEvents = $this->service->getBetween(CalendarRequestDTO::from($eventRequest->validated()));

        return EventResource::collection($requestedEvents);
    }
}
