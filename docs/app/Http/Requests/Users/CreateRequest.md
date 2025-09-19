# CreateRequest

Request для создания пользователя.

#### Правила валидации:

* `name` — required, string, max:255.
* `email` — required, string, email, max:255, уникальное значение в таблице `users`.
* `password` — required, string, min:3, max:255, подтверждение пароля обязательно (`confirmed`).
* `department_id` — nullable, int, существует в таблице `departments`, учитывает `deleted_at`.
* `position_id` — nullable, int, существует в таблице `positions`, учитывает `deleted_at`.
* `gender` — required, значение из перечисления `GenderEnum`.
* `status` — required, значение из перечисления `StatusEnum`.
* `role` — nullable, int, существует в таблице `roles`.
