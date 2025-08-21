<?php

declare(strict_types=1);

namespace App\Services\Tasks;

use App\DTO\User\Department\DepartmentDTO;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\DTO\Tasks\Event\CreateEventDTO;
use App\DTO\Tasks\Event\CalendarRequestDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\Tasks\EventRepositoryInterface;

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
     * Удалить событие
     * 
     * @param int $eventId
     * @return void
     */
    public function delete(int $eventId): void
    {
        $eventToDetete = $this->repository->find($eventId);

        $this->repository->delete($eventToDetete);
    }
}
