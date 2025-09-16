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
     * Получить события по датам определенного пользователя
     *
     * @param string $start
     * @param string $end
     * @param \App\Models\User $user
     * @return Collection<int, Event>
     */
    public function betweenByUser(string $start, string $end, User $user): Collection;

    /**
     * Получить выполненные задачи по пользователю
     * @param string $start
     * @param string $end
     * @param User $user
     * @return Collection
     */
    public function betweenByUserIsDone(string $start, string $end, User $user): Collection;

    /**
     * Получить выполненные события всех пользователей в диапазоне
     *
     * @param string $start
     * @param string $end
     * @return \Illuminate\Support\Collection
     */
    public function betweenByAllUsersIsDone(string $start, string $end): \Illuminate\Support\Collection;

    /**
     * Получить текущие видимые события
     *
     * @param string $start
     * @param string $end
     * @return Collection
     */
    public function getCurrentVisible(string $start, string $end): Collection;

    /**
     * Создать новое событие
     * @param CreateEventDTO $dto
     * @return Event
     */
    public function create(CreateEventDTO $dto): Event;

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
     * @return Event
     */
    public function update(Event $updateEvent, PatchEventDTO $dto): Event;

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

    /**
     * Summary of findModel
     * @param int $eventId
     * @return Event
     */
    public function findModel(int $eventId): Event;
}
