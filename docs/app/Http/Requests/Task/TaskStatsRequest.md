# TaskStatsRequest

Request для получения статистики по задачам.

#### Правила валидации:

* `start` — required, date (начальная дата периода).
* `end` — required, date (конечная дата периода).
* `user_id` — nullable, int (ID пользователя, по которому фильтруется статистика).
