<?php

namespace App\Http\Controllers\Tasks;

use App\DTO\Tasks\Stats\RequestStatsDTO;
use App\Http\Requests\Task\TaskStatsRequest;
use Carbon\Carbon;
use App\Models\Tasks\Event;
use App\DTO\Tasks\Event\EventDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Tasks\EventService;
use App\DTO\Tasks\Event\PatchEventDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\Http\Requests\Task\EventRequest;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\Http\Resources\Tasks\EventResource;
use App\Http\Requests\Task\PatchEventRequest;
use App\Http\Requests\Task\CreateEventRequest;
use App\Http\Resources\Tasks\EventViewResource;
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
        $this->authorize('check-permission', 'tasks-create');

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
     * @return EventViewResource
     */
    public function show(int $eventid): EventViewResource
    {
        $this->authorize('check-permission', 'tasks-read');

        return new EventViewResource($this->repository->find($eventid));
    }

    /**
     * Обновить событие
     *
     * @param CreateEventRequest $createEventRequest
     * @param int $eventId
     * @return void
     */
    public function update(CreateEventRequest $createEventRequest, int $eventId): void
    {
        $eventToUpdate = $this->repository->findModel($eventId);

        $this->authorize('update', $eventToUpdate);

        $dto = PatchEventDTO::from($createEventRequest->validated());

        $this->service->update($eventToUpdate, $dto);
    }

    public function patch(PatchEventRequest $patchEventRequest, int $eventId)
    {
        $eventToUpdate = $this->repository->findModel($eventId);

        $this->authorize('update', $eventToUpdate);

        $dto = PatchEventDTO::from($patchEventRequest->validated());

        $this->service->update($eventToUpdate, $dto);
    }

    /**
     * Удалить событие
     *
     * @param int $eventId
     * @return void
     */
    public function destroy(int $eventId): void
    {
        $eventToDelete = $this->repository->find($eventId);

        $this->authorize('delete', $eventToDelete);

        $this->service->delete($eventToDelete);
    }

    public function mark(int $eventId)
    {
        $this->authorize('check-permission', 'tasks-read');

        $eventToMark = $this->repository->find($eventId);

        $this->service->markEventAsDone($eventToMark);
    }


    public function stats(TaskStatsRequest $taskStatsRequest)
    {
        $requestStatsDto = RequestStatsDTO::from($taskStatsRequest->validated());

        $statsChartDTO = $this->service->getStats($requestStatsDto);

        return response()->json([
            'categories' => $statsChartDTO->categories,
            'data' => $statsChartDTO->data
        ]);
    }

    public function amountTimeStats(TaskStatsRequest $taskStatsRequest)
    {
        $requestStatsDto = RequestStatsDTO::from($taskStatsRequest->validated());

        $statsChartDTO = $this->service->getAmountTimeStats($requestStatsDto);

        return response()->json([
            'categories' => $statsChartDTO->categories,
            'data' => $statsChartDTO->data
        ]);
    }
}
