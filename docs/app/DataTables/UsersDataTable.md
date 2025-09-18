# UsersDataTable

Класс для формирования таблицы пользователей для DataTable.
Реализует фильтрацию, сортировку и пагинацию, а также отображение связанных данных (отдел, должность, роли).

#### Использует:

* [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)
* [UserRepositoryInterface](/app/Repositories/Interfaces/User/UserRepositoryInterface.md)
* [User](/app/Models/User/User.md)

#### Поля:

* `UserRepositoryInterface $repository` — репозиторий для работы с пользователями.

---

#### Методы:

##### dataTable

```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы `EloquentDataTable`. Форматирует модель `User`:

* `department_id` — выводит имя отдела.
* `position_id` — выводит имя должности.
* `gender` — локализованное отображение пола пользователя.
* `status` — локализованное отображение статуса пользователя.
* `created_at`, `updated_at` — формат времени `H:i d.m.Y`.
* `roles` — если есть разрешение `roles-read`, выводит роль пользователя.

Вход: `QueryBuilder<User>` — результаты метода `query()`.

Выход: `EloquentDataTable`.

##### query

```php
public function query(DatatableRequestDTO $dto): QueryBuilder
```

Возвращает построитель запроса с применением фильтрации, сортировки и поиска по имени, а также с подгрузкой связанных моделей (`department`, `position`, `roles`).

Вход:

* `DatatableRequestDTO $dto` — DTO с данными о странице, сортировке и поиске.

Выход: `QueryBuilder<User>` — подготовленный запрос.

Особенности:

* Сортировка по ролям выполняется через `leftJoin` таблиц `role_user` и `roles`, если есть разрешение `roles-read`.

* Поиск по имени выполняется только если есть разрешение `users-find`.

---

##### json

```php
public function json(DatatableRequestDTO $dto): \Illuminate\Http\JsonResponse
```

Возвращает JSON-ответ для DataTable с пагинацией, фильтром и количеством записей.

Вход:

* `DatatableRequestDTO $dto` — DTO с данными о странице, сортировке и поиске.

Выход: JSON с полями:

* `data` — данные таблицы в формате DataTable.
* `recordsFiltered` — количество найденных записей после фильтрации.
* `draw` — счетчик запросов DataTable.
* `input` — исходные данные DTO.
