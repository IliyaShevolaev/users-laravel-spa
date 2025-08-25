<?php

declare(strict_types=1);

namespace App\Services\Tasks;

use App\Models\User;
use App\DTO\User\UserDTO;
use App\Models\Tasks\Event;
use App\Models\Tasks\EventUser;
use App\Enums\Role\SystemRolesEnum;
use Illuminate\Support\Facades\Auth;
use App\DTO\Tasks\Event\PatchEventDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\DTO\User\Department\DepartmentDTO;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\Tasks\Event\EventUserRelationDTO;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private DepartmentRepositoryInterface $departmentRepository,
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository
    ) {
    }

    /**
     * Получить события между датами
     *
     * @param CalendarRequestDTO $calendarRequestDTO
     * @return Collection
     */
    public function getBetween(CalendarRequestDTO $calendarRequestDTO): Collection
    {
        return $this->repository->getCurrentVisible($calendarRequestDTO->start, $calendarRequestDTO->end);
    }

    /**
     * Создать событие
     *
     * @param CreateEventDTO $dto
     * @return void
     */
    public function create(CreateEventDTO $dto): void
    {
        $this->repository->create($dto);
    }

    /**
     * Получить данные для создания события
     *
     * @return array
     */
    public function prepareCreateData(): array
    {
        $user = Auth::user();

        $usersToAssign = $this->getSubordinates($user);

        $assigningUser = UserDTO::from($user);

        return ['users' => $usersToAssign, 'user' => $assigningUser];
    }

    /**
     * Обновить данные о событии
     *
     * @param Event $updateEvent
     * @param PatchEventDTO $dto
     * @return void
     */
    public function update(Event $updateEvent, PatchEventDTO $dto)
    {
        $this->repository->update($updateEvent, $dto);
    }

    /**
     * Удалить событие
     *
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void
    {
        $this->repository->delete($event);
    }

    /**
     * Пометить задачу выполненной
     *
     * @param Event $event
     * @return void
     */
    public function markEventAsDone(Event $event)
    {
        $markRelation = EventUser::where('user_id', Auth::id())->where('event_id', $event->id)->firstOrFail();

        $markRelation->is_done = !$markRelation->is_done;
        $markRelation->save();
    }

    /**
     * Получить коллекцию подчиненных
     *
     * @param User $user
     * @return Collection
     */
    private function getSubordinates(User $user)
    {
        $userRoleName = $user->roles()->first()?->name;

        $rolesToAssign = collect();

        if ($userRoleName === SystemRolesEnum::Admin->value) {
            $rolesToAssign->push(SystemRolesEnum::User->value);
            $rolesToAssign->push(SystemRolesEnum::Manager->value);
            $rolesToAssign->push(SystemRolesEnum::Admin->value);

        } else if ($userRoleName === SystemRolesEnum::Manager->value) {
            $rolesToAssign->push(SystemRolesEnum::User->value);
        }

        if ($rolesToAssign->isEmpty()) {
            return collect();
        }

        return UserDTO::collect(User::whereHas('roles', function ($query) use ($rolesToAssign) {
            $query->whereIn('name', $rolesToAssign->toArray());
        })->where('id', '!=', $user->id)->get());
    }
}
