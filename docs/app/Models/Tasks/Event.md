# Event

Модель события.

#### Использует:

[User](/app/Models/User/User.md), [EventUser](/app/Models/Tasks/EventUser.md)

[EventPolicy](/app/Policies/EventPolicy.md)

[SystemRolesEnum](/app/Enums/Role/SystemRolesEnum.md)

#### Поля

* `string $title` — название события.

* `string $description` — описание события.

* `string $start` — дата и время начала события.

* `string $end` — дата и время окончания события.

* `int $creator_id` — идентификатор создателя события.

* `string $created_at` — дата создания записи.

* `string $updated_at` — дата обновления записи.


#### Связи

* `users()` — связь `belongsToMany` с моделью [User](/app/Models/User/User.md) через таблицу `event_user` (используется Pivot-модель [EventUser](/app/Models/Tasks/EventUser.md)).

* `creator()` — связь `belongsTo` с моделью [User](/app/Models/User/User.md), создатель события.

#### Методы

* `canUserChange(User $user)` — проверка, может ли пользователь изменять событие (по ролям и авторству).

* `getIsDoneAttribute()` — статус выполнения события для текущего пользователя.

* `getEndTimeAttribute()` — время завершения события пользователе.
