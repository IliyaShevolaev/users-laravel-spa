# DepartmentRequest

Request для создания или редактирования отдела.

#### Правила валидации:

* `name` — required, string, max:255, уникальное значение в таблице `departments` (игнорирует текущий ID при обновлении, учитывает soft delete).

