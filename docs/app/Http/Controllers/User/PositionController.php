<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\DataTables\PositionsDataTable;
use App\DTO\User\Position\PositionDTO;
use App\DTO\DataTable\DatatableRequestDTO;
use App\DTO\User\Position\CreatePositionDTO;
use App\Http\Resources\User\PositionResource;
use App\Services\User\Position\PositionService;
use App\Http\Requests\DataTables\DataTableRequest;
use App\Http\Requests\Users\Position\PositionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

/**
 * Контрллер должностей пользователей
 *
 * @uses PositionService
 */
class PositionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Сервис для контроллера
     * @var PositionService
     *
     * Реаозиторий для представления данных для отделов
     * @var PositionRepositoryInterface
     */
    public function __construct(
        private PositionService $service,
        private PositionRepositoryInterface $repository
    ) {
    }

    /**
     * Отображает все должности через таблицу PositionsDataTable
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('check-permission', 'positions-read');

        return PositionResource::collection($this->repository->all());
    }

    /**
     * Возвращает таблицу должностей
     *
     * @param PositionsDataTable $positionsDataTable
     * @return JsonResponse
     */
    public function dataTable(DataTableRequest $dataTableRequest, PositionsDataTable $positionsDataTable): JsonResponse
    {
        $this->authorize('check-permission', 'positions-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $positionsDataTable->json($dto);
    }

    /**
     * Сохраняет новую должность
     *
     * @param PositionRequest $positionRequest
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function store(PositionRequest $positionRequest): JsonResponse
    {
        $this->authorize('check-permission', 'positions-create');

        $dto = CreatePositionDTO::from($positionRequest->validated());

        $this->service->create($dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Возвращает форму редактирования передаваемой должности
     *
     * @param int $positionId
     * @return PositionResource
     */
    public function edit(int $positionId): PositionResource
    {
        $this->authorize('check-permission', 'positions-update');

        $positionToEdit = $this->repository->find($positionId);

        return new PositionResource($positionToEdit);
    }

    /**
     * Обновляет должность
     *
     * @param PositionRequest $positionRequest
     * @param int $positionId
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function update(PositionRequest $positionRequest, int $positionId): JsonResponse
    {
        $this->authorize('check-permission', 'positions-update');

        $dto = CreatePositionDTO::from($positionRequest->validated());

        $this->service->update($positionId, $dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удаляет должность при отсутствии связей
     *
     * @param int $positionId
     * @return JsonResponse 200 - {'message' => 'success'} | 409 - {'message' => 'delete not allowed'}
     */
    public function destroy(int $positionId): JsonResponse
    {
        $this->authorize('check-permission', 'positions-delete');

        $deleteResult = $this->service->delete($positionId);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
