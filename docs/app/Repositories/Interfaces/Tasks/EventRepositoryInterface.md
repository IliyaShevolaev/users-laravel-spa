# EventRepositoryInterface

Интерфейс репозитория для работы с событиями и задачами пользователей.
Обеспечивает методы CRUD-операций, выборки событий в диапазоне дат и управление связями пользователя с выполненными событиями.

#### Использует:

[Event](/app/Models/Tasks/Event.md), [User](/app/Models/User/User.md)
[CreateEventDTO](/app/DTO/Tasks/Event/CreateEventDTO.md), [PatchEventDTO](/app/DTO/Tasks/Event/PatchEventDTO.md), [EventUserRelationDTO](/app/DTO/Tasks/Event/EventUserRelationDTO.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех событий.

Выход: коллекция `EventDTO`.

##### between

```php
public function between(string $start, string $end): Collection
```

Получение событий в указанном временном диапазоне.

Вход: даты `$start`, `$end`.

Выход: коллекция `EventDTO`.

##### betweenByUser

```php
public function betweenByUser(string $start, string $end, User $user): Collection
```

Получение событий определённого пользователя в указанном временном диапазоне.

Вход: даты `$start`, `$end` + модель `User`.

Выход: коллекция `EventDTO`.


##### betweenByUserIsDone

```php
public function betweenByUserIsDone(string $start, string $end, User $user): Collection
```

Получение выполненных событий определённого пользователя в указанном временном диапазоне.

Вход: даты `$start` + `$end`, модель `User`.

Выход: коллекция `EventDTO`.

---

##### betweenByAllUsersIsDone

```php
public function betweenByAllUsersIsDone(string $start, string $end): Collection
```

Получение выполненных событий всех пользователей в указанном временном диапазоне.

Вход: даты `$start`, `$end`.

Выход: коллекция `EventDTO`.

##### getCurrentVisible

```php
public function getCurrentVisible(string $start, string $end): Collection
```

Получение событий, на которые назначен текущий пользователь.

Вход: даты `$start` + `$end`.

Выход: коллекция `EventDTO`.


##### create

```php
public function create(CreateEventDTO $dto): Event
```

Создание нового события.

Вход: `CreateEventDTO`.

Выход: модель `Event`.

---

##### find

```php
public function find(int $eventId): Event
```

Поиск события по ID с подгрузкой его автора.

Вход: ID события.

Выход: модель `Event`.

##### findModel

```php
public function findModel(int $eventId): Event
```

Поиск события по ID без дополнительных связей.

Вход: ID события.

Выход: модель `Event`.

---

##### update

```php
public function update(Event $updateEvent, PatchEventDTO $dto): Event
```

Обновление события новыми данными.

Вход: модель `Event` + `PatchEventDTO`.

Выход: обновлённая модель `Event`.

##### delete

```php
public function delete(Event $event): void
```

Удаление события.

Вход: модель `Event`.

Выход: –

##### makeEventUserRelation

```php
public function makeEventUserRelation(EventUserRelationDTO $dto): void
```

Создание связи пользователя с событием в pivot таблцие (пометка выполнения).

Вход: `EventUserRelationDTO`.

Выход: –

##### deleteEventUserRelation

```php
public function deleteEventUserRelation(EventUserRelationDTO $dto): void
```

Удаление связи пользователя с событием в pivot таблцие (пометка выполнения).

Вход: `EventUserRelationDTO`.

Выход: –

##### getUserCompletedEvents

```php
public function getUserCompletedEvents(User $user): Collection
```

Получение списка завершённых событий пользователя.

Вход: модель `User`.

Выход: коллекция `EventDTO`.

##### checkRelation

```php
public function checkRelation(EventUserRelationDTO $dto)
```

Проверка, выполнил ли пользователь событие.

Вход: `EventUserRelationDTO`.

Выход: `bool`.
