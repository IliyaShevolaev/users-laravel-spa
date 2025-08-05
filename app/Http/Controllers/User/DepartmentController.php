<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTO\User\Department\DepartmentDTO;
use App\Models\User\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
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
     * Отображает все отделы через таблицу DepartmentsDataTable
     *
     * @return JsonResponse|View
     *
     * @param DepartmentsDataTable $departmentsDataTable
     */
    public function index(DepartmentsDataTable $departmentsDataTable): JsonResponse|View
    {
        return $departmentsDataTable->render('departments.index');
    }

    /**
     * Возвращает форму создания нового отдела
     *
     * @return JsonResponse
     */
    public function create()
    {
        return response()->json(view('departments.form')
            ->with(['route' => route('departments.store')])
            ->render());
    }

    /**
     * Сохраняет новый отдел
     *
     * @param DepartmentRequest $departmentRequest
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function store(DepartmentRequest $departmentRequest): JsonResponse
    {
        $dto = DepartmentDTO::from($departmentRequest->validated());

        $this->service->create($dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Возвращает форму редактирования передаваемого отдела
     *
     * @param int $department_id
     * @return JsonResponse
     */
    public function edit(int $department_id)
    {
        $departmentToEdit = $this->repository->find($department_id);

        return response()->json(view('departments.form')
            ->with([
                'route' => route('departments.update', $departmentToEdit->id),
                'element' => $departmentToEdit
            ])->render());
    }

    /**
     * Обновляет отдел
     *
     * @param DepartmentRequest $departmentRequest
     * @param int $department_id
     * @return JsonResponse 200 - {'message' => 'success'}
     */
    public function update(DepartmentRequest $departmentRequest, int $department_id): JsonResponse
    {
        $dto = DepartmentDTO::from($departmentRequest->validated());

        $this->service->update($department_id, $dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удаляет отдел при отсутствии связей
     *
     * @param int $department_id
     * @return JsonResponse 200 - {'message' => 'success'} | 409 - {'message' => 'delete not allowed'}
     */
    public function destroy(int $department_id): JsonResponse
    {
        $deleteResult = $this->service->delete($department_id);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
