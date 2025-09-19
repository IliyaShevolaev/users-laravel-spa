# RoleRepositoryInterface

Интерфейс репозитория для работы с ролями.
Реализует CRUD-операции и получение связанных данных.

#### Использует:

[RoleDTO](/app/DTO/Roles/RoleDTO.md), [CreateRoleDTO](/app/DTO/Roles/CreateRoleDTO.md), [UserDTO](/app/DTO/User/UserDTO.md)

[Role](/app/Models/Roles/Role.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех ролей.

Выход: коллекция `RoleDTO`.

---

##### create

```php
public function create(CreateRoleDTO $createRoleDTO): Role
```

Создание новой роли.

Вход: `CreateRoleDTO`
Выход: модель `Role`.

---

##### update

```php
public function update(CreateRoleDTO $dto, Role $role): void
```

Обновление роли по её модели.

Вход: `CreateRoleDTO`, модель `Role`
Выход: –

---

##### delete

```php
public function delete(Role $role): void
```

Удаление роли.

Вход: модель `Role`
Выход: –

---

##### find

```php
public function find(int $roleId): Role
```

Поиск роли по ID.

Вход: `int $roleId` — ID роли
Выход: модель `Role`.

---

##### findWithPermissions

```php
public function findWithPermissions(int $roleId): Role
```

Поиск роли по ID с подгруженными разрешениями.

Вход: `int $roleId` — ID роли
Выход: модель `Role` с отношением `permissions`.

---

##### count

```php
public function count(): int
```

Подсчёт общего числа ролей.

Выход: количество ролей (`int`).

---

##### getQuery

```php
public function getQuery(): Builder
```

Получение построителя запросов для ролей.

Выход: `Builder`.

---

##### findRelatedUsers

```php
public function findRelatedUsers(Role $role): Collection
```

Получение пользователей, связанных с данной ролью.

Вход: модель `Role`
Выход: коллекция `UserDTO`.
