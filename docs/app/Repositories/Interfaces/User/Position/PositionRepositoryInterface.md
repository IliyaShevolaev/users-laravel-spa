
# PositionRepositoryInterface

Интерфейс репозитория для работы с должностями пользователей.

#### Использует:

[Position](/app/Models/User/Position.md)

[CreatePositionDTO](/app/DTO/User/Position/CreatePositionDTO.md), [PositionDTO](/app/DTO/User/Position/PositionDTO.md), [UserDTO](/app/DTO/User/UserDTO.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех должностей в виде DTO `PositionDTO`.

Выход: коллекция `PositionDTO`.

##### create

```php
public function create(CreatePositionDTO $data): void
```

Создание новой должности.

Вход: `CreatePositionDTO`

Выход: —

##### update

```php
public function update(int $positionId, CreatePositionDTO $data): void
```

Обновление существующей должности.

Вход: ID должности, `CreatePositionDTO`

Выход: —

##### delete

```php
public function delete(Position $position): void
```

Удаление должности.

Вход: модель `Position`

Выход: —

##### find

```php
public function find(int $positionId): Position
```

Поиск должности по ID.

Вход: ID должности

Выход: модель `Position`

##### findRelatedUsers

```php
public function findRelatedUsers(Position $position): Collection
```

Получение пользователей, связанных с конкретной должностью, в виде DTO `UserDTO`.

Вход: модель `Position`

Выход: коллекция `UserDTO`

##### getQuery

```php
public function getQuery(): Builder
```

Возвращает построитель запросов для модели `Position` (для фильтрации, поиска или пагинации).

Выход: `Builder<Position>`

##### count

```php
public function count(): int
```

Возвращает количество всех записей должностей.

Выход: число записей
