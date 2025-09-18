# PositionRequest

Request для создания или редактирования должности.

#### Правила валидации:

* `name` — required, string, max:255, уникальное значение в таблице `positions` (игнорирует текущий ID при обновлении, учитывает soft delete).
