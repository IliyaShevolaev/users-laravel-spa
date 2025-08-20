<?php

namespace App\Http\Controllers\Tasks;

use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateEventRequest;
use App\Http\Requests\Task\EventRequest;
use App\Http\Resources\Tasks\EventResource;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Services\Tasks\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private EventService $service
    ) {
    }

    /**
     * получить список всех событий по времени
     *
     * @param EventRequest $eventRequest
     * @return AnonymousResourceCollection
     */
    public function index(EventRequest $eventRequest): AnonymousResourceCollection
    {
        $requestedEvents = $this->service->getBetween(CalendarRequestDTO::from($eventRequest->validated()));

        return EventResource::collection($requestedEvents);
    }

    /**
     * Создать новое событие
     * 
     * @param CreateEventRequest $createEventRequest
     * @return void
     */
    public function store(CreateEventRequest $createEventRequest): void
    {
        $dto = CreateEventDTO::from($createEventRequest->validated());

        $this->service->create($dto);
    }
}
