<?php

namespace App\Http\Controllers\Tasks;

use App\DTO\Tasks\Event\EventDTO;
use App\Models\Tasks\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Tasks\EventService;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\Http\Requests\Task\EventRequest;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\Http\Resources\Tasks\EventResource;
use App\Http\Requests\Task\CreateEventRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    use AuthorizesRequests;

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
        $this->authorize('check-permission', 'tasks-read');

        $requestedEvents = $this->service->getBetween(CalendarRequestDTO::from($eventRequest->validated()));

        return EventResource::collection($requestedEvents);
    }

    /**
     * Получить данные для создания события
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $this->authorize('create', Event::class);

        $data = $this->service->prepareCreateData();

        return response()->json($data);
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

        $this->authorize('store', $dto);

        $this->service->create($dto);
    }

    /**
     * Найти информацию о событии
     * 
     * @param int $eventid
     * @return EventResource
     */
    public function show(int $eventid)
    {
        return new EventResource(EventDTO::from($this->repository->find($eventid)));
    }

    /**
     * Удалить событие
     *
     * @param int $eventId
     * @return void
     */
    public function destroy(int $eventId): void
    {
        $this->service->delete($eventId);
    }
}
