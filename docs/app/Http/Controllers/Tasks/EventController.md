# EventController

Контроллер для управления событиями (Tasks).
Реализует CRUD-операции, отметку о выполнении событий и получение статистики по событиям пользователей.

#### Использует:

[EventRequest](/app/Http/Requests/Task/EventRequest.md), [CreateEventRequest](/app/Http/Requests/Task/CreateEventRequest.md), [PatchEventRequest](/app/Http/Requests/Task/PatchEventRequest.md), [MarkEventRequest](/app/Http/Requests/Task/MarkEventRequest.md), [TaskStatsRequest](/app/Http/Requests/Task/TaskStatsRequest.md)

[CreateEventDTO](/app/DTO/Tasks/Event/CreateEventDTO.md), [PatchEventDTO](/app/DTO/Tasks/Event/PatchEventDTO.md), [MarkEventDTO](/app/DTO/Tasks/Event/MarkEventDTO.md), [CalendarRequestDTO](/app/DTO/Tasks/Event/CalendarRequestDTO.md), [RequestStatsDTO](/app/DTO/Tasks/Stats/RequestStatsDTO.md)

[EventService](/app/Services/Tasks/EventService.md)

[EventRepositoryInterface](/app/Repositories/Interfaces/Tasks/EventRepositoryInterface.md)

[EventResource](/app/Http/Resources/Tasks/EventResource.md), [EventViewResource](/app/Http/Resources/Tasks/EventViewResource.md)

#### Методы

##### index

```php
public function index(EventRequest $eventRequest): AnonymousResourceCollection
```

Получение списка событий за указанный период.

Разрешения: tasks-read

Вход: EventRequest с периодом дат

Выход: коллекция `EventResource`

##### create

```php
public function create(): JsonResponse
```

Получение данных для создания события. Получение пользователей в подчинении и текущего пользователя.

Разрешения: tasks-create

Вход: –

Выход:
```json
{
    "users": [[...], [...], [...]], 
    "user": [...]
}
```

##### store

```php
public function store(CreateEventRequest $createEventRequest): void
```

Создание нового события.

Разрешения: tasks-create

Вход: `CreateEventRequest`

Выход: –

##### show

```php
public function show(int $eventId): EventViewResource
```

Получение детальной информации о событии в виде ресурса.

Разрешения: tasks-read

Вход: ID события

Выход: `EventViewResource`

##### update

```php
public function update(CreateEventRequest $createEventRequest, int $eventId): void
```

Обновление данных о событии.

Разрешения: tasks-update

Вход: ID события + `CreateEventRequest`

Выход: –

##### patch

```php
public function patch(PatchEventRequest $patchEventRequest, int $eventId)
```

Обновление даты и времени события (используется при drag and drop события в календаре).

Разрешения: tasks-update

Вход: ID события + `PatchEventRequest`

Выход: –

##### destroy

```php
public function destroy(int $eventId): void
```

Удаление события по его ID.

Разрешения: tasks-delete

Вход: ID события

Выход: –

##### mark

```php
public function mark(MarkEventRequest $markEventRequest, int $eventId)
```

Отметка события как выполненного в определённое время.

Разрешения: tasks-read

Вход: ID события + `MarkEventRequest`
Выход: –

##### unmark

```php
public function unmark(int $eventId)
```

Снятие отметки о выполненном событии.

Разрешения: tasks-read

Вход: ID события

Выход: –

##### amountStats

```php
public function amountStats(TaskStatsRequest $taskStatsRequest)
```

Получение статистики по количеству событий.

Разрешения: tasks-read

Вход: `TaskStatsRequest`

Выход: JSON с категориями и данными графика

##### amountTimeStats

```php
public function amountTimeStats(TaskStatsRequest $taskStatsRequest)
```

Получение статистики по времени выполнения событий.

Разрешения: tasks-read

Вход: `TaskStatsRequest`

Выход: JSON с категориями и данными графика
