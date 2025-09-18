# ChangeCalendarEvent

Событие, которое транслируется через приватный канал (change.calendar.events.{userId}) при изменении события в календаре.
Уведомляет клиента о том, что событие создано или изменено, а также о новой назначенной задаче.

#### Использует:

[EventDTO](/app/DTO/Tasks/Event/EventDTO.md)

[EventNotifyResource](/app/Http/Resources/Tasks/EventNotifyResource.md) 

#### Поля

* `int $userId` — ID пользователя, которому предназначено уведомление
* `EventDTO $event` — объект события
* `bool $isNewAssign` — флаг, указывающий на то, что событие назначено новому пользователю


Выход:

```json
{
  "userId": 1,
  "event": { ... },
  "isNewAssign": true
}
```
