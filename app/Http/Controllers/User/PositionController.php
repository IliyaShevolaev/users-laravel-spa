<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTO\User\Position\CreatePositionDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\DataTables\PositionsDataTable;
use App\DTO\User\Position\PositionDTO;
use App\Http\Resources\User\PositionResource;
use App\Services\User\Position\PositionService;
use App\Http\Requests\Users\Position\PositionRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

/**
 * Контрллер должностей пользователей
 *
 * @uses PositionService
 */
class PositionController extends Controller
{
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
        return PositionResource::collection($this->repository->all());
    }

    public function dataTable(PositionsDataTable $positionsDataTable)
    {
        return $positionsDataTable->ajax();
    }

    /**
     * Сохраняет новую должность
     *
     * @param PositionRequest $positionRequest
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function store(PositionRequest $positionRequest): JsonResponse
    {
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
        $deleteResult = $this->service->delete($positionId);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
