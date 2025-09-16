# CityRequest

Request для создания или редактирования города.

#### Правила валидации:

* `name` — required, string, max:255, уникальное значение в таблице cities (игнорирует текущий ID при обновлении).

* `ip_start` — required, string – начальный IP-адрес.

* `ip_end` — required, string – конечный IP-адрес.

* `region_id` — required, int, должно существовать в таблице regions по полю id.
