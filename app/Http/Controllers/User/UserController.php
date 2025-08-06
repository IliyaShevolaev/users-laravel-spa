<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTO\User\UserDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\UsersDataTable;
use App\Services\User\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
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
        return UserResource::collection($this->repository->allWithRelations());
    }

    /**
     * Отображает страницу создания нового пользователя
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $data = $this->service->prepareViewData();

        return response()->json($data);
    }

    /**
     * Сохраняет нового пользователя и редиректит на таблицу с пользователями
     *
     * @param CreateRequest $createRequest
     * @return RedirectResponse
     */
    public function store(CreateRequest $createRequest): RedirectResponse
    {
        $dto = UserDTO::from($createRequest->validated());

        $this->service->create($dto);

        return redirect()->route('users.index');
    }

    /**
     * Отображает страницу редактирования пользователя
     *
     * @param int $user_id
     * @return JsonResponse
     */
    public function edit(int $user_id): JsonResponse
    {
        Log::info('here');
        $data = $this->service->prepareViewData($user_id);

        return response()->json($data);
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param EditRequest $editRequest
     * @param int $user_id
     * @return JsonResponse
     */
    public function update(EditRequest $editRequest, int $user_id): JsonResponse
    {
        $dto = UserDTO::from($editRequest->validated());

        $this->service->update($dto, $user_id);

        return response()->json(['message' => 'success']);
    }

    /**
     * Удаляет пользователя
     *
     * @param int $user_id
     * @return JsonResponse
     */
    public function destroy(int $user_id): JsonResponse
    {
        $this->service->delete($user_id);

        return response()->json([
            'message' => 'success',
        ]);
    }
}
