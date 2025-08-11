<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTO\User\Department\CreateDepartmentDTO;
use App\DTO\User\Department\DepartmentDTO;
use App\Http\Resources\User\DepartmerntResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\DataTables\DepartmentsDataTable;
use App\Services\User\Department\DepartmentService;
use App\Http\Requests\Users\Department\DepartmentRequest;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

/**
 * Контрллер отделов пользователей
 *
 * @uses DepartmentService
 * @uses DepartmentRepositoryInterface
 */
class DepartmentController extends Controller
{
    /**
     * Сервис для контроллера
     * @var DepartmentService
     *
     * Реаозиторий для представления данных для отделов
     * @var DepartmentRepositoryInterface
     */
    public function __construct(
        private DepartmentService $service,
        private DepartmentRepositoryInterface $repository
    ) {
    }

    /**
     * Возвращает коллекцию ресурсов
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return DepartmerntResource::collection($this->repository->all());
    }

    /**
     * Возвращает Json ответ таблицы
     *
     * @param DepartmentsDataTable $departmentsDataTable
     * @return JsonResponse
     */
    public function datatable(DepartmentsDataTable $departmentsDataTable): JsonResponse
    {
        return $departmentsDataTable->ajax();
    }

    /**
     * Сохраняет новый отдел
     *
     * @param DepartmentRequest $departmentRequest
     * @return JsonResponse 200 - {'data' => new department}
     */
    public function store(DepartmentRequest $departmentRequest): JsonResponse
    {
        $dto = CreateDepartmentDTO::from($departmentRequest->validated());

        $this->service->create($dto);

        return response()->json(['data' => 'success']);
    }

    /**
     * Возвращает редактируемый отдел
     *
     * @param int $departmentId
     * @return DepartmerntResource
     */
    public function edit(int $departmentId): DepartmerntResource
    {
        $departmentToEdit = $this->repository->find($departmentId);

        return new DepartmerntResource($departmentToEdit);
    }

    /**
     * Обновляет отдел
     *
     * @param DepartmentRequest $departmentRequest
     * @param int $departmentId
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function update(DepartmentRequest $departmentRequest, int $departmentId): JsonResponse
    {
        $dto = CreateDepartmentDTO::from($departmentRequest->validated());

        $this->service->update($departmentId, $dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удаляет отдел при отсутствии связей
     *
     * @param int $departmentId
     * @return JsonResponse 200 - {'message' => 'success'} | 409 - {'message' => 'delete not allowed'}
     */
    public function destroy(int $departmentId): JsonResponse
    {
        $deleteResult = $this->service->delete($departmentId);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
