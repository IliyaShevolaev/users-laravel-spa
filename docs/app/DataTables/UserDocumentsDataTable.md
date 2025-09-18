# UserDocumentsDataTable

Класс для формирования таблицы документов пользователей для DataTable.

#### Использует:

* [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

* [UserDocumentRepositoryInterface](/app/Repositories/Interfaces/User/UserDocumentRepositoryInterface.md)

* [UserDocument](/app/Models/User/UserDocument.md)

#### Поля:

* `UserDocumentRepositoryInterface $repository` — репозиторий для работы с пользовательскими документами.

#### Методы:

##### dataTable

```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель `UserDocument`:
* Изменяет поле created_at — формат времени на d.m.Y H:i.
* Изменяет поле updated_at — формат времени на d.m.Y H:i.

Вход: `QueryBuilder<UserDocument>` — результаты метода `query()`.

Выход: `EloquentDataTable`.


##### query

```php
public function query(DatatableRequestDTO $dto, int $userId): QueryBuilder
```

Возвращает построитель запроса с применением фильтрации, сортировки и поиска по имени и по конкретному пользователю.

Вход:

* `DatatableRequestDTO $dto` — DTO с данными о странице, сортировке и поиске.

* `int $userId` — ID пользователя, чьи документы выводятся.

Выход: `QueryBuilder<UserDocument>` — подготовленный запрос.


##### json

```php
public function json(DatatableRequestDTO $dto, int $userId): \Illuminate\Http\JsonResponse
```

Возвращает JSON-ответ для DataTable с пагинацией, фильтром и количеством записей.

Вход:

* `DatatableRequestDTO $dto` — DTO с данными о странице, сортировке и поиске.
 
* `int $userId` — ID пользователя, чьи документы выводятся.

Выход: JSON с полями:

* `data` — данные таблицы в формате DataTable.
* `recordsFiltered` — количество найденных записей после фильтрации.
* `draw` — счетчик запросов DataTable.
* `input` — исходные данные DTO.

