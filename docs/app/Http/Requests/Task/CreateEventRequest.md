# CreateEventRequest

Request для создания события в календаре.

#### Правила валидации:

* `title` — required, string, max:255.
* `description` — required, string, max:65535.
* `start` — required, date.
* `end` — required, date.
* `user_id` — required, array, min:1 (должен быть выбран хотя бы один пользователь).
* `user_id.*` — required (каждое значение в массиве обязательно).
* `creator_id` — required, int (ID создателя события).

#### Названия атрибутов:

* `title` — локализованное название из `trans('main.title')`.
* `start` — локализованное название из `trans('main.date_range')`.
* `end` — локализованное название из `trans('main.date_range')`.
* `user_id.*` — локализованное название из `trans('main.must_user_assign')`.

