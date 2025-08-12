<?php

namespace App\Http\Controllers\Role;

use App\DTO\Roles\CreateRoleDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\RolesDataTable;
use App\Services\Roles\RoleService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RoleRequest;
use App\Http\Resources\Roles\RoleResource;

class RoleController extends Controller
{

    /**
     * Сервис для взаимодействия с ролями
     *
     * @var RoleService $service
     */
    public function __construct(private RoleService $service)
    {
    }

    /**
     * Возвращает таблицу ролей
     *
     * @param RolesDataTable $rolesDataTable
     * @return JsonResponse
     */
    public function dataTable(RolesDataTable $rolesDataTable): JsonResponse
    {
        return $rolesDataTable->ajax();
    }

    /**
     * Создать новую роль
     *
     * @param RoleRequest $roleRequest
     * @return JsonResponse
     */
    public function store(RoleRequest $roleRequest): JsonResponse
    {
        $dto = CreateRoleDTO::from($roleRequest->validated());

        $this->service->store($dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Получить данные о роли для редактирования
     *
     * @param int $roleId
     * @return RoleResource
     */
    public function edit(int $roleId): RoleResource
    {
        $roleToEdit = $this->service->edit($roleId);

        return new RoleResource($roleToEdit);
    }

    /**
     * Обновить роль
     *
     * @param RoleRequest $roleRequest
     * @param int $roleId
     * @return JsonResponse
     */
    public function update(RoleRequest $roleRequest, int $roleId): JsonResponse
    {
        $dto = CreateRoleDTO::from($roleRequest->validated());

        $this->service->update($dto, $roleId);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удалть роль
     *
     * @param int $roleId
     * @return JsonResponse
     */
    public function destroy(int $roleId): JsonResponse
    {
        $this->service->delete($roleId);

        return response()->json(['message' => 'success']); // 409!
    }
}
