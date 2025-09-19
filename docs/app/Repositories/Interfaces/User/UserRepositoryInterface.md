# UserRepositoryInterface

Интерфейс репозитория для работы с пользователями.
Реализует CRUD-операции и получение связанных данных из базы.

#### Использует:

[UserDTO](/app/DTO/User/UserDTO.md), [CreateUserDTO](/app/DTO/User/CreateUserDTO.md)
[User](/app/Models/User.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех активных пользователей, кроме системного.

Выход: коллекция моделей `User`.

##### allWithUnactive

```php
public function allWithUnactive(): Collection
```

Получение всех пользователей, включая неактивных.

Выход: коллекция моделей `User`.

##### create

```php
public function create(CreateUserDTO $dto): User
```

Создание нового пользователя.

Вход: `CreateUserDTO`

Выход: модель `User`.

##### update

```php
public function update(int $userId, CreateUserDTO $dto): User
```

Обновление данных пользователя по ID.

Вход: ID пользователя, `CreateUserDTO`

Выход: модель `User`.

##### delete

```php
public function delete(User $user): void
```

Удаление пользователя.

Вход: модель `User`

Выход: –

##### find

```php
public function find(int $userId): User
```

Поиск пользователя по ID.

Вход: ID пользователя

Выход: модель `User`.

##### withoutScopeFind

```php
public function withoutScopeFind(int $userId): UserDTO
```

Поиск пользователя по ID без применения глобальных scope.

Вход: ID пользователя

Выход: `UserDTO` с данными пользователя и ролью.

##### getQueryWithRelations

```php
public function getQueryWithRelations(array $relations): Builder
```

Получение построителя запросов с указанными отношениями и без глобального scope.

Вход: массив отношений для подгрузки

Выход: `Builder<User>`.

##### count

```php
public function count(): int
```

Подсчёт общего числа пользователей.

Выход: количество пользователей.

##### getRelatedRole

```php
public function getRelatedRole(int $userId)
```

Получение первой роли пользователя по ID.

Вход: ID пользователя

Выход: `RoleDTO` или `null`, если роль отсутствует.
