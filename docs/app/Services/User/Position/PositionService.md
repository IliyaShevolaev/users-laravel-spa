# PositionService

Сервис для управления должностями пользователей.

#### Использует:

[PositionRepositoryInterface](/app/Repositories/Interfaces/User/Position/PositionRepositoryInterface.md) 
[CreatePositionDTO](/app/DTO/User/Position/CreatePositionDTO.md)
[MessageDTO](/app/DTO/MessageDTO.md)

#### Методы

##### create

```php
public function create(CreatePositionDTO $dto): void
```

Создаёт новую должность пользователя.

Вход: `CreatePositionDTO $dto` — DTO с данными новой должности

Выход: —

##### update

```php
public function update(int $positionId, CreatePositionDTO $dto): void
```

Обновляет должность по её ID.

Вход:

* `int $positionId` — ID должности

* `CreatePositionDTO $dto` — DTO с обновлёнными данными

Выход: —

##### delete

```php
public function delete(int $positionId): MessageDTO
```

Удаляет должность пользователя, если отсутствуют пользователи с этой должностью.

Вход: `int $positionId` — ID должности

Выход: `MessageDTO` — сообщение о результате операции
