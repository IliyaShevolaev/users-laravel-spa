<?php

declare(strict_types=1);

namespace App\Services\Tasks;

use App\Models\Tasks\Event;
use Illuminate\Support\Facades\Auth;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\DTO\User\Department\DepartmentDTO;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private DepartmentRepositoryInterface $departmentRepository
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
        return $this->repository->getCurrentVisible($calendarRequestDTO->start, $calendarRequestDTO->end, Auth::user()->department_id);
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
        $departmentsToAssign =  null;
        $user = Auth::user();

        if ($user->hasPermission('tasks-createAll')) {
            $departmentsToAssign = $this->departmentRepository->all();
        } else {
            if (isset($user->department_id)) {
                $departmentsToAssign = DepartmentDTO::collect([$this->departmentRepository->find($user->department_id)]);
            } else {
                $departmentsToAssign = DepartmentDTO::collect([]);
            }
        }

        return ['departments' => $departmentsToAssign];
    }

    /**
     * Обновить данные о событии
     *
     * @param Event $updateEvent
     * @param CreateEventDTO $dto
     * @return void
     */
    public function update( Event $updateEvent, CreateEventDTO $dto)
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
}
