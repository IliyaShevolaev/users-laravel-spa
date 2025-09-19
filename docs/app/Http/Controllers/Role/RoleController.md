# RoleController

Контроллер для управления ролями.
Реализует CRUD-операции и работу с таблицей ролей.

#### Использует:

[RoleRequest](/app/Http/Requests/Roles/RoleRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreateRoleDTO](/app/DTO/Roles/CreateRoleDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[RoleService](/app/Services/Roles/RoleService.md)

[RoleRepositoryInterface](/app/Repositories/Interfaces/Roles/RoleRepositoryInterface.md)

[RoleResource](/app/Http/Resources/Roles/RoleResource.md)

[RolesDataTable](/app/DataTables/RolesDataTable.md)

#### Методы

##### dataTable

```php
public function dataTable(DataTableRequest $dataTableRequest, RolesDataTable $rolesDataTable): JsonResponse
```

Получение данных для отображения ролей в формате DataTable.

Разрешения: `roles-read`

Вход: `DataTableRequest` (параметры таблицы)

Выход: JSON-ответ с данными таблицы

##### store

```php
public function store(RoleRequest $roleRequest): void
```

Создание новой роли.

Разрешения: `roles-create`

Вход: `RoleRequest`

Выход: –

##### edit

```php
public function edit(int $roleId): RoleResource
```

Получение данных роли для редактирования.

Разрешения: `roles-update`

Вход: ID роли

Выход: `RoleResource`

##### update

```php
public function update(RoleRequest $roleRequest, int $roleId): void
```

Обновление роли.

Разрешения: `roles-update`

Вход: ID роли + `RoleRequest`

Выход: –


##### destroy

```php
public function destroy(int $roleId): JsonResponse
```

Удаление роли.

Разрешения: `delete-role`

Вход: ID роли

Выход: JSON с сообщением о результате операции
