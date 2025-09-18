
# DepartmentRepositoryInterface

Интерфейс репозитория для работы с отделами пользователей.

#### Использует:

[Department](/app/Models/User/Department.md)

[CreateDepartmentDTO](/app/DTO/User/Department/CreateDepartmentDTO.md), [DepartmentDTO](/app/DTO/User/Department/DepartmentDTO.md), [UserDTO](/app/DTO/User/UserDTO.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех отделов в виде DTO `DepartmentDTO`.

Выход: коллекция `DepartmentDTO`.

##### create

```php
public function create(CreateDepartmentDTO $dto): void
```

Создание нового отдела.

Вход: `CreateDepartmentDTO`

Выход: —

##### update

```php
public function update(int $departmentId, CreateDepartmentDTO $dto): void
```

Обновление существующего отдела.

Вход: ID отдела, `CreateDepartmentDTO`

Выход: —

##### delete

```php
public function delete(Department $department): void
```

Удаление отдела.

Вход: модель `Department`

Выход: —

##### find

```php
public function find(int $departmentId): Department
```

Поиск отдела по ID.

Вход: ID отдела

Выход: модель `Department`

##### findRelatedUsers

```php
public function findRelatedUsers(Department $department): Collection
```

Получение пользователей, связанных с конкретным отделом, в виде DTO `UserDTO`.

Вход: модель `Department`

Выход: коллекция `UserDTO`

##### getQuery

```php
public function getQuery(): Builder
```

Возвращает построитель запросов для модели `Department` (для фильтрации, поиска или пагинации).

Выход: `Builder<Department>`

##### count

```php
public function count(): int
```

Возвращает количество всех записей отделов.

Выход: количество записей

