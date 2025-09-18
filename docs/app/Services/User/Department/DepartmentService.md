# DepartmentService

Сервис для управления отделами пользователей.

#### Использует:

[DepartmentRepositoryInterface](/app/Repositories/Interfaces/User/Department/DepartmentRepositoryInterface.md)

[CreateDepartmentDTO](/app/DTO/User/Department/CreateDepartmentDTO.md)отдела, [MessageDTO](/app/DTO/MessageDTO.md)

#### Методы

##### create

```php
public function create(CreateDepartmentDTO $dto): void
```

Создаёт новый отдел пользователя.

Вход: `CreateDepartmentDTO $dto` — DTO с данными нового отдела

Выход: —

##### update

```php
public function update(int $departmentId, CreateDepartmentDTO $dto): void
```

Обновляет отдел по его ID.

Вход:

* `int $departmentId` — ID отдела

* `CreateDepartmentDTO $dto` — DTO с обновлёнными данными

Выход: —

##### delete

```php
public function delete(int $departmentId): MessageDTO
```

Удаляет отдел пользователя, если отсутствуют пользователи с этим отделом.

Вход: `int $departmentId` — ID отдела
Выход: `MessageDTO` — сообщение о результате операции 

