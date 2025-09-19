# EditRequest

Request для редактирования пользователя.

#### Правила валидации:

* `name` — required, string, max:255.
* `email` — required, string, email, max:255.
* `password` — nullable, string, min:3, max:255, подтверждение пароля обязательно (`confirmed`).
* `department_id` — nullable, int, существует в таблице `departments`.
* `position_id` — nullable, int, существует в таблице `positions`.
* `gender` — required, значение из перечисления `GenderEnum`.
* `status` — required, значение из перечисления `StatusEnum`.
* `role` — nullable, int.
