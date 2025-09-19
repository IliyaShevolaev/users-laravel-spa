# UserController

Контроллер для управления пользователями.
Реализует CRUD-операции и работу с таблицей пользователей.

#### Использует:

[CreateRequest](/app/Http/Requests/Users/CreateRequest.md), [EditRequest](/app/Http/Requests/Users/EditRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreateUserDTO](/app/DTO/User/CreateUserDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[UserService](/app/Services/User/UserService.md)

[UserRepository](/app/Repositories/User/UserRepository.md)

[UserResource](/app/Http/Resources/User/UserResource.md)

[UsersDataTable](/app/DataTables/UsersDataTable.md)

#### Методы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получение списка всех пользователей.

Разрешения: `users-read`

Вход: –

Выход: коллекция `UserResource`

##### datatable

```php
public function datatable(DataTableRequest $dataTableRequest, UsersDataTable $usersDataTable): JsonResponse
```

Получение данных для отображения пользователей в формате DataTable.

Разрешения: `users-read`

Вход: `DataTableRequest` (параметры таблицы)

Выход: JSON-ответ с данными таблицы

##### create

```php
public function create(): JsonResponse
```

Получение данных, необходимых для создания пользователя.

Разрешения: `change-user`

Вход: –

Выход: Json с данными для формы создания пользователя

##### store

```php
public function store(CreateRequest $createRequest): void
```

Создание нового пользователя.

Разрешения: `users-create`

Вход: `CreateRequest`

Выход:

##### edit

```php
public function edit(int $userId): JsonResponse
```

Получение данных пользователя для редактирования.

Разрешения: `users-update`

Вход: ID пользователя

Выход: Json с данными пользователя

##### update

```php
public function update(EditRequest $editRequest, int $userId): void
```

Обновление данных пользователя.

Разрешения: `users-update`

Вход: ID пользователя + `EditRequest`

Выход: -

##### destroy

```php
public function destroy(int $userId): void
```

Удаление пользователя.

Разрешения: `delete-user`

Вход: ID пользователя

Выход: -

##### getUserRole

```php
public function getUserRole(int $userId): Role
```

Получение роли пользователя по его ID.

Разрешения: `roles-read`

Вход: ID пользователя

Выход: данные роли пользователя
