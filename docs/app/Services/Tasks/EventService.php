<?php

declare(strict_types=1);

namespace App\Services\Tasks;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\DTO\User\UserDTO;
use App\Models\Tasks\Event;
use App\Models\Tasks\EventUser;
use App\DTO\Tasks\Event\EventDTO;
use App\Enums\Role\SystemRolesEnum;
use App\Events\ChangeCalendarEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\DTO\Tasks\Event\PatchEventDTO;
use App\DTO\Tasks\Stats\StatsChartDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\DTO\Tasks\Stats\RequestStatsDTO;
use App\Utilities\TaskStats\TaskTimeStats;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use App\Utilities\TaskStats\TaskAmountStats;
use Illuminate\Database\Eloquent\Collection;
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
        private RoleRepositoryInterface $roleRepository,
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
        $createdEvent = $this->repository->create($dto);

        $createdEventUsers = $createdEvent->users();
        $createdEventUsers->attach($dto->userId);

        $createdEvent->load('creator');
        $eventDto = EventDTO::from($createdEvent);
        $createdEventUsers->get()->each(function (User $user) use ($eventDto) {
            broadcast(new ChangeCalendarEvent($user->id, $eventDto, true));
        });
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
    public function update(Event $updateEvent, PatchEventDTO $dto): void
    {
        $event = $this->repository->update($updateEvent, $dto);

        $oldUsers = $updateEvent->users()->get();

        if (!empty($dto->userId)) {
            $event->users()->sync($dto->userId);
        }

        $allUsers = $updateEvent->users()->get();
        $newUserIds = $allUsers->pluck('id')->diff($oldUsers->pluck('id'));

        $event->load('creator');
        $eventDto = EventDTO::from($event);

        $allUsers->each(function (User $user) use ($eventDto, $newUserIds) {
            broadcast(new ChangeCalendarEvent($user->id, $eventDto, $newUserIds->contains($user->id)));
        });
    }

    /**
     * Удалить событие
     *
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void
    {
        $users = $event->users()->get();

        $event->load('creator');
        $eventDto = EventDTO::from($event);

        $users->each(function (User $user) use ($eventDto) {
            broadcast(new ChangeCalendarEvent($user->id, $eventDto, false));
        });

        $this->repository->delete($event);
    }

    /**
     * Пометить задачу выполненной
     *
     * @param Event $event
     * @param string|null $endTime
     * @return void
     */
    public function markEvent(Event $event, string|null $endTime)
    {
        $markRelation = EventUser::where('user_id', Auth::id())->where('event_id', $event->id)->firstOrFail();

        $markRelation->is_done = !$markRelation->is_done;
        $markRelation->end_time = $endTime;
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

    /**
     * Получить статистику по количеству событий
     *
     * @param RequestStatsDTO $requestStatsDTO
     * @return StatsChartDTO
     */
    public function getAmountStats(RequestStatsDTO $requestStatsDTO)
    {
        $rangeStart = Carbon::parse($requestStatsDTO->start)->startOfDay();
        $rangeEnd = Carbon::parse($requestStatsDTO->end)->endOfDay();

        if (isset($requestStatsDTO->userId)) {
            $currentUser = $this->userRepository->find($requestStatsDTO->userId);
            $events = $this->repository->betweenByUser(
                $rangeStart->toDateTimeString(),
                $rangeEnd->toDateTimeString(),
                $currentUser
            );
        } else {
            $events = $this->repository->between(
                $rangeStart->toDateTimeString(),
                $rangeEnd->toDateTimeString()
            );
        }

        return TaskAmountStats::handle($events, $rangeStart, $rangeEnd);
    }

    /**
     * Получить статистику по времени событий
     *
     * @param RequestStatsDTO $requestStatsDTO
     * @return StatsChartDTO
     */
    public function getAmountTimeStats(RequestStatsDTO $requestStatsDTO)
    {
        $rangeStart = Carbon::parse($requestStatsDTO->start)->startOfDay();
        $rangeEnd = Carbon::parse($requestStatsDTO->end)->endOfDay();

        if (isset($requestStatsDTO->userId)) {
            $currentUser = $this->userRepository->find($requestStatsDTO->userId);
            $events = $this->repository->betweenByUserIsDone(
                $rangeStart->toDateTimeString(),
                $rangeEnd->toDateTimeString(),
                $currentUser
            );
        } else {
            $events = $this->repository->betweenByAllUsersIsDone(
                $rangeStart->toDateTimeString(),
                $rangeEnd->toDateTimeString()
            );
        }

        return TaskTimeStats::handle($events, $rangeStart, $rangeEnd);
    }
}
