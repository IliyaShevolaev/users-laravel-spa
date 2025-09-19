# PositionController

Контроллер для управления должностями пользователей.
Реализует операции CRUD и работу с таблицей должностей.

#### Использует:

[PositionRequest](/app/Http/Requests/Users/Position/PositionRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreatePositionDTO](/app/DTO/User/Position/CreatePositionDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[PositionService](/app/Services/User/Position/PositionService.md)

[PositionRepositoryInterface](/app/Repositories/Interfaces/User/Position/PositionRepositoryInterface.md)

[PositionResource](/app/Http/Resources/User/PositionResource.md)

[PositionsDataTable](/app/DataTables/PositionsDataTable.md)

---

#### Методы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получение списка всех должностей в виде коллекции ресурсов.

Разрешения: `positions-read`

Вход: –

Выход: коллекция `PositionResource`

---

##### dataTable

```php
public function dataTable(DataTableRequest $dataTableRequest, PositionsDataTable $positionsDataTable): JsonResponse
```

Получение данных для отображения должностей в формате DataTable.

Разрешения: `positions-read`

Вход: `DataTableRequest` (параметры таблицы)

Выход: JSON-ответ с данными таблицы

##### store

```php
public function store(PositionRequest $positionRequest): void
```

Создание новой должности.

Разрешения: `positions-create`

Вход: `PositionRequest`

Выход: -

##### edit

```php
public function edit(int $positionId): PositionResource
```

Получение должности для редактирования.

Разрешения: `positions-update`

Вход: ID должности

Выход: `PositionResource`

##### update

```php
public function update(PositionRequest $positionRequest, int $positionId): void
```

Обновление данных должности.

Разрешения: `positions-update`

Вход: ID должности + `PositionRequest`

Выход: -

##### destroy

```php
public function destroy(int $positionId): JsonResponse
```

Удаление должности при отсутствии зависимостей.

Разрешения: `positions-delete`

Вход: ID должности

Выход:

* status code — 200

```json
{
  "message": "success"
}
```

* status code — 409

```json
{
  "message": "delete not allowed"
}
```
