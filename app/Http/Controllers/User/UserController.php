<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use App\DTO\User\CreateUserDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\UsersDataTable;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\EditRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Users\CreateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Контроллер пользователей
 *
 * @uses \App\Services\User\UserService
 */
class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Сервис пользователей
     * @var UserService $service
     * @var UserRepository $repository
     */
    public function __construct(
        protected UserService $service,
        protected UserRepository $repository
    ) {
    }

    /**
     * Отображает всех пользователей через таблицу UserDataTable
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('check-permission', 'users-read');

        return UserResource::collection($this->repository->allWithRelations());
    }

    public function datatable(UsersDataTable $usersDataTable): JsonResponse
    {
        $this->authorize('check-permission', 'users-read');

        return $usersDataTable->ajax();
    }

    /**
     * Возвращает json данных, необходимых для создания пользователя
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $this->authorize('change-user');

        $data = $this->service->prepareViewData();

        return response()->json($data);
    }

    /**
     * Сохраняет нового пользователя и редиректит на таблицу с пользователями
     *
     * @param CreateRequest $createRequest
     *
     * @return JsonResponse
     */
    public function store(CreateRequest $createRequest): JsonResponse
    {
        $this->authorize('check-permission', 'users-create');


        $dto = CreateUserDTO::from($createRequest->validated());

        $this->service->create($dto);

        return response()->json(['message' => 'success']);
    }

    /**
     * Возвращает json данных, необходимых для обновления пользователя
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function edit(int $userId): JsonResponse
    {
        $this->authorize('check-permission', 'users-update');

        $data = $this->service->prepareViewData($userId);

        return response()->json($data);
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param EditRequest $editRequest
     * @param int $userId
     * @return JsonResponse
     */
    public function update(EditRequest $editRequest, int $userId): JsonResponse
    {
        $this->authorize('check-permission', 'users-update');

        $dto = CreateUserDTO::from($editRequest->validated());

        $this->service->update($dto, $userId);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удаляет пользователя
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function destroy(int $userId): JsonResponse
    {
        $this->authorize('check-permission', 'users-delete');

        $deleteResult = $this->service->delete($userId);

        return response()->json([], $deleteResult->code);

    }

    /**
     * Получить роль пользователя по id
     *
     * @param int $userId
     */
    public function getUserRole(int $userId)
    {
        $this->authorize('check-permission', 'roles-read');

        return $this->repository->getRelatedRole($userId);
    }
}
