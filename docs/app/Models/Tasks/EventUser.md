# EventUser

Модель pivot-таблицы `event_user`, связывающей пользователей и события. Использует `LogsActivity` для логирования назначений пользователей на события.

#### Использует:

[User](/app/Models/User/User.md), [Event](/app/Models/Tasks/Event.md)

#### Поля

* `int $user_id` — идентификатор пользователя.
* `int $event_id` — идентификатор события.
* `bool $is_done` — статус выполнения задачи пользователем.

#### Связи

* `user()` — связь `belongsTo` с моделью [User](/app/Models/User/User.md).
* `event()` — связь `belongsTo` с моделью [Event](/app/Models/Tasks/Event.md).

#### Особенности

* Логируются только изменения поля`is_done` (отметка о выполнении/снятие отметки) и создание (назначение на событие).
