# EventService

Сервис для управления задачами и их статистикой.

#### Использует:

[EventRepositoryInterface](/app/Repositories/Interfaces/Tasks/EventRepositoryInterface.md), [DepartmentRepositoryInterface](/app/Repositories/Interfaces/User/Department/DepartmentRepositoryInterface.md)
[UserRepositoryInterface](/app/Repositories/Interfaces/User/UserRepositoryInterface.md)
[RoleRepositoryInterface](/app/Repositories/Interfaces/Roles/RoleRepositoryInterface.md)

[Event](/app/Models/Tasks/Event.md), [EventUser](/app/Models/Tasks/EventUser.md), [User](/app/Models/User/User.md)

[CreateEventDTO](/app/DTO/Tasks/Event/CreateEventDTO.md), [PatchEventDTO](/app/DTO/Tasks/Event/PatchEventDTO.md), [CalendarRequestDTO](/app/DTO/Tasks/Event/CalendarRequestDTO.md), [EventDTO](/app/DTO/Tasks/Event/EventDTO.md), [MarkEventDTO](/app/DTO/Tasks/Event/MarkEventDTO.md), [RequestStatsDTO](/app/DTO/Tasks/Stats/RequestStatsDTO.md), [StatsChartDTO](/app/DTO/Tasks/Stats/StatsChartDTO.md)

`TaskAmountStats`, `TaskTimeStats`

`ChangeCalendarEvent`

#### Методы

##### getBetween

```php
public function getBetween(CalendarRequestDTO $calendarRequestDTO): Collection
```

Получение всех видимых пользователю событий между двумя датами.

Вход: `CalendarRequestDTO` с датами начала и конца диапазона.

Выход: коллекция `Event`.

##### create

```php
public function create(CreateEventDTO $dto): void
```

Создание нового события назначенным на него пользователям и оповещение участников через `ChangeCalendarEvent`.

Вход: `CreateEventDTO` — DTO с данными события.
Выход: –

##### prepareCreateData

```php
public function prepareCreateData(): array
```

Подготовка данных для создания события. Получение пользователей в подчинении и текущего пользователя.

Вход: –
Выход: массив с `users` (коллекция `UserDTO`) и `user` (`UserDTO` текущего пользователя)

##### update

```php
public function update(Event $updateEvent, PatchEventDTO $dto): void
```

Обновление события и синхронизация назначенных пользователей.

Отправляет `ChangeCalendarEvent` пользователям, которым назначенно это событие.

Вход: `Event` — модель изменяемого события + `PatchEventDTO` — DTO с обновлёнными данными.

Выход: –

##### delete

```php
public function delete(Event $event): void
```

Удаление события и отправка `ChangeCalendarEvent` всего его участникам.

Вход: `Event` — модель события.

Выход: –

##### markEvent

```php
public function markEvent(Event $event, string|null $endTime)
```

Смена флага о выполнении события пользователем. При снятии этой отметки `endTime` передается и устанавливается на null.

Вход: `Event` — событие, `endTime` — время выполнения.

Выход: –

##### getSubordinates

```php
private function getSubordinates(User $user)
```

Получение подчинённых пользователей для назначения задач в зависимости от роли текущего пользователя.

Подчиненные роли `admin` — все пользователи
Подчиненные роли `manager` — все пользователи с ролью `user`.
Подчиненные роли `user` — нет подчиненных вернется пустая коллекция.

Вход: `User` — текущий пользователь по которому ищем подчиненных.

Выход: коллекция `UserDTO`.

##### getAmountStats

```php
public function getAmountStats(RequestStatsDTO $requestStatsDTO)
```

Получение статистики по количеству задач за период. Если передан пользователь то собираем его задачи, иначе собираем все. Далее статитика считается через Ulility класс `TaskAmountStats`.

Вход: `RequestStatsDTO` с диапазоном дат и необязательным ID пользователя.

Выход: `StatsChartDTO` с категориями и данными для дальнейшего представления в виде графика.

##### getAmountTimeStats

```php
public function getAmountTimeStats(RequestStatsDTO $requestStatsDTO)
```

Получение статистики по времени выполнения задач за период. Если передан пользователь то собираем его отмеченные выполненным задачи, иначе собираем все. Далее статитика считается через Ulility класс `TaskTimeStats`.

Вход: `RequestStatsDTO` с диапазоном дат и необязательным ID пользователя.

Выход: `StatsChartDTO` с категориями и данными графика.
