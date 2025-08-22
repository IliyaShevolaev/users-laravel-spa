<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\Tasks;

use App\Models\User;
use App\Models\Tasks\Event;
use App\DTO\Tasks\Event\PatchEventDTO;
use App\DTO\Tasks\Event\CreateEventDTO;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\Tasks\Event\EventUserRelationDTO;

interface EventRepositoryInterface
{
    /**
     * Получить все события
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Получить события в промежутке
     *
     * @param string $start
     * @param string $end
     * @return Collection
     */
    public function between(string $start, string $end): Collection;

    /**
     * Получить текущие видимые события
     *
     * @param string $start
     * @param string $end
     * @param int|null $department_id
     * @return Collection
     */
    public function getCurrentVisible(string $start, string $end, int|null $department_id): Collection;

    /**
     * Создать новое событие
     * @param CreateEventDTO $dto
     * @return void
     */
    public function create(CreateEventDTO $dto): void;

    /**
     * Найти событие по id
     *
     * @param int $eventId
     * @return Event
     */
    public function find(int $eventId): Event;

    /**
     * Обновить событие
     * @param Event $updateEvent
     * @param PatchEventDTO $dto
     * @return void
     */
    public function update(Event $updateEvent, PatchEventDTO $dto);

    /**
     * Удалить событие
     *
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void;

    /**
     * Создать пометку выполненого задания пользователем
     *
     * @param EventUserRelationDTO $dto
     * @return void
     */
    public function makeEventUserRelation(EventUserRelationDTO $dto);

    /**
     * Удалить связь пользователя с выполненной задачей
     *
     * @param EventUserRelationDTO $dto
     * @return void
     */
    public function deleteEventUserRelation(EventUserRelationDTO $dto): void;

    /**
     * Получить выполненные задания по пользователю
     *
     * @param User $user
     * @return Collection
     */
    public function getUserCompletedEvents(User $user);

    /**
     * Проверить сделал ли пользоватль задачу
     *
     * @param EventUserRelationDTO $dto
     */
    public function checkRelation(EventUserRelationDTO $dto);
}
