# DepartmentController

Контроллер для управления отделами пользователей.
Реализует операции CRUD и работу с таблицей отделов.

#### Использует:

[DepartmentRequest](/app/Http/Requests/Users/Department/DepartmentRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreateDepartmentDTO](/app/DTO/User/Department/CreateDepartmentDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[DepartmentService](/app/Services/User/Department/DepartmentService.md)

[DepartmentRepositoryInterface](/app/Repositories/Interfaces/User/Department/DepartmentRepositoryInterface.md)

[DepartmerntResource](/app/Http/Resources/User/DepartmerntResource.md)

[DepartmentsDataTable](/app/DataTables/DepartmentsDataTable.md)

---

#### Методы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получение списка всех отделов в виде коллекции ресурсов.

Разрешения: `departments-read`

Вход: –

Выход: коллекция `DepartmerntResource`

---

##### datatable

```php
public function datatable(DataTableRequest $dataTableRequest, DepartmentsDataTable $departmentsDataTable): JsonResponse
```

Получение данных для отображения отделов в формате DataTable.

Разрешения: `departments-read`

Вход: `DataTableRequest` (параметры таблицы)

Выход: JSON-ответ с данными таблицы

##### store

```php
public function store(DepartmentRequest $departmentRequest): void
```

Создание нового отдела.

Разрешения: `departments-create`

Вход: `DepartmentRequest`

Выход: -

##### edit

```php
public function edit(int $departmentId): DepartmerntResource
```

Получение отдела для редактирования.

Разрешения: `departments-update`

Вход: ID отдела

Выход: `DepartmerntResource`

##### update

```php
public function update(DepartmentRequest $departmentRequest, int $departmentId): void
```

Обновление данных отдела.

Разрешения: `departments-update`

Вход: ID отдела + `DepartmentRequest`

Выход: -

##### destroy

```php
public function destroy(int $departmentId): JsonResponse
```

Удаление отдела при отсутствии зависимостей.

Разрешения: `departments-delete`

Вход: ID отдела

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
