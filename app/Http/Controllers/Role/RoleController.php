<?php

namespace App\Http\Controllers\Role;

use App\Models\Roles\Role;
use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\RolesDataTable;
use App\Services\Roles\RoleService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RoleRequest;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RoleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Сервис для взаимодействия с ролями
     *
     * @var RoleService $service
     */
    public function __construct(private RoleService $service, private RoleRepositoryInterface $repository)
    {
    }

    /**
     * Возвращает таблицу ролей
     *
     * @param RolesDataTable $rolesDataTable
     * @return JsonResponse
     */
    public function dataTable(DataTableRequest $dataTableRequest, RolesDataTable $rolesDataTable): JsonResponse
    {
        $this->authorize('check-permission', 'roles-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $rolesDataTable->json($dto, 'display_name');
    }

    /**
     * Создать новую роль
     *
     * @param RoleRequest $roleRequest
     * @return JsonResponse
     */
    public function store(RoleRequest $roleRequest): void
    {
        $this->authorize('check-permission', 'roles-create');

        $dto = CreateRoleDTO::from($roleRequest->validated());

        $this->service->store($dto);
    }

    /**
     * Получить данные о роли для редактирования
     *
     * @param int $roleId
     * @return RoleResource
     */
    public function edit(int $roleId): RoleResource
    {
        $this->authorize('check-permission', 'roles-update');

        $roleToEdit = $this->service->edit($roleId);

        return new RoleResource($roleToEdit);
    }

    /**
     * Обновить роль
     *
     * @param RoleRequest $roleRequest
     * @param int $roleId
     * @return void
     */
    public function update(RoleRequest $roleRequest, int $roleId): void
    {
        $this->authorize('check-permission', 'roles-update');

        $currentRole = $this->repository->find($roleId);

        $dto = CreateRoleDTO::from($roleRequest->validated());

        $this->service->update($dto, $currentRole);
    }

    /**
     * Удалть роль
     *
     * @param int $roleId
     * @return JsonResponse
     */
    public function destroy(int $roleId): JsonResponse
    {
        $currentRole = $this->repository->find($roleId);
        $this->authorize('delete-role', $currentRole);

        $deleteResult = $this->service->delete($currentRole);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
