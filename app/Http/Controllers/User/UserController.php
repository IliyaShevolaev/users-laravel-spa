<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\DTO\User\UserDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\UsersDataTable;
use App\Services\User\UserService;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Users\EditRequest;
use App\Http\Requests\Users\CreateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
     *
     * @var UserService $service
     */
    public function __construct(protected UserService $service)
    {
    }

    /**
     * Отображает всех пользователей через таблицу UserDataTable
     *
     * @param UsersDataTable $usersDataTable
     * @return JsonResponse|View
     */
    public function index(UsersDataTable $usersDataTable): JsonResponse|View
    {
        return $usersDataTable->render('users.table-index');
    }

    /**
     * Отображает страницу создания нового пользователя
     *
     * @return View
     */
    public function create(): View
    {
        $data = $this->service->prepareViewData();

        return view('users.change-user-table', [
            'departments' => $data->departments,
            'positions' => $data->positions
        ]);
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
     * @return View
     */
    public function edit(int $user_id): View
    {
        $dto = $this->service->prepareViewData($user_id);

        return view('users.change-user-table', [
            'user' => $dto->userDTO,
            'departments' => $dto->departments,
            'positions' => $dto->positions
        ]);
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param EditRequest $editRequest
     * @param int $user_id
     * @return RedirectResponse
     */
    public function update(EditRequest $editRequest, int $user_id): RedirectResponse
    {
        $dto =  UserDTO::from($editRequest->validated());

        $this->service->update($dto, $user_id);

        return redirect()->route('users.index');
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
