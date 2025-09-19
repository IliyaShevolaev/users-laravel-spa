# RoleService

Сервис для работы с ролями системы.

#### Использует:

[RoleRepositoryInterface](/app/Repositories/Interfaces/Roles/RoleRepositoryInterface.md)

[CreateRoleDTO](/app/DTO/Roles/CreateRoleDTO.md), [MessageDTO](/app/DTO/MessageDTO.md)

[ChangeRolePermissions](/app/Events/ChangeRolePermissions.md)

[SystemRolesEnum](/app/Enums/Role/SystemRolesEnum.md)

#### Методы

##### store

```php
public function store(CreateRoleDTO $createRoleDTO): void
```

Создаёт новую роль. Присваивает переданные разрешения к ней.

Вход:

* `CreateRoleDTO $createRoleDTO` — DTO с данными новой роли.

Выход: —

##### edit

```php
public function edit(int $roleId): Role
```

Возвращает модель роли с загруженными разрешениями для редактирования.

Вход:

* `int $roleId` — ID роли.

Выход: `Role` — модель роли с разрешениями.

##### update

```php
public function update(CreateRoleDTO $createRoleDTO, Role $role): void
```

Обновляет данные роли и синхронизирует её разрешения. После обновления уведомляет пользователей через `ChangeRolePermissions` о том, что разрешения роли обновились и пользователю необходимо перезапросить разрешения.

Вход:

* `CreateRoleDTO $createRoleDTO` — DTO с новыми данными роли.
* `Role $role` — модель обновляемой роли.

Выход: —

##### delete

```php
public function delete(Role $role): MessageDTO
```

Удаляет роль, если у неё нет связанных пользователей.

Вход:

* `Role $role` — модель удаляемой роли.

Выход:

* `MessageDTO` — сообщение о результате операции, с кодом статуса.
