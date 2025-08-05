<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\DTO\User\UserDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Interfaces\User\UserRepositoryInterface;

/**
 * Сервис для автризации
 */
class Service
{
    /**
     * Реаозиторий для представления данных для пользователей
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Получить массив для дальнейшего редиректа
     *
     * @param string $route
     * @param mixed $failedError
     * @return array{failed: string, route: string}
     */
    private function getLoginResultInfo(string $route, mixed $failedError = ''): array
    {
        return [
            'route' => $route,
            'failed' => is_string($failedError) ? $failedError : ''
        ];
    }

    /**
     * Регистрирует нового пользователя
     *
     * @param UserDTO $dto
     * @return void
     */
    public function registerStore(UserDTO $dto): void
    {
        /** @var User $user */

        $user = $this->repository->create($dto);

        Auth::login($user);
    }

    /**
     * Проверяет данные пользователя, а так же его статус активности при полном соответсвии выполняет вход
     *
     * Возвращает на страницу входа с errors failed при неудачной попытке входа
     *
     * @param LoginRequest $loginRequest
     * @param array<string> $data
     * @return array{failed: string, route: string}
     */
    public function loginStore(LoginRequest $loginRequest, array $data): array
    {
        $result = [];

        /** @var \Illuminate\Database\Eloquent\Builder <\App\Models\User> $query */
        $query = User::where('email', $data['email']);

        $userExists = $query->exists();
        if ($userExists) {
            $remember = isset($data['remember']);
            unset($data['remember']);

            if (Auth::attempt($data, $remember)) {
                /** @var User $user */
                $user = Auth::user();
                if ($user->status === 'unactive') {
                    return $this->getLoginResultInfo('login', __('auth.unactive'));
                }

                $loginRequest->session()->regenerate();

                $result['route'] = 'users.index';
                $result['failed'] = '';
                return $this->getLoginResultInfo('users.index');
            }
        }

        return $this->getLoginResultInfo('login', __('auth.failed'));
    }

    /**
     * Выход пользователя из системы
     *
     * @param Request $request
     * @return void
     */
    public function logoutUser(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
