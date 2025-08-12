<?php

namespace App\Http\Controllers\Role;

use App\DataTables\RolesDataTable;
use App\DTO\Roles\CreateRoleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RoleRequest;
use App\Services\Roles\RoleService;
use Illuminate\Http\JsonResponse;

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
}
