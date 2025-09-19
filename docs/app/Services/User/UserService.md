# UserService

Сервис для работы с пользователями.

#### Использует:

[UserRepositoryInterface](/app/Repositories/Interfaces/User/UserRepositoryInterface.md)

[DepartmentRepositoryInterface](/app/Repositories/Interfaces/User/Department/DepartmentRepositoryInterface.md)

[PositionRepositoryInterface](/app/Repositories/Interfaces/User/Position/PositionRepositoryInterface.md)

[RoleRepositoryInterface](/app/Repositories/Interfaces/Roles/RoleRepositoryInterface.md)

[CreateUserDTO](/app/DTO/User/CreateUserDTO.md), [UserDTO](/app/DTO/User/UserDTO.md), [UserRelatedDTO](/app/DTO/User/UserRelatedDTO.md), [MessageDTO](/app/DTO/MessageDTO.md)

[GenderEnum](/app/Enums/User/GenderEnum.md), [StatusEnum](/app/Enums/User/StatusEnum.md)

[ChangeUserRole](/app/Events/ChangeUserRole.md)

#### Методы

##### create

```php
public function create(CreateUserDTO $dto): void
```

Создаёт нового пользователя. И при наличии переданной роли, задает ему эту роль.

Вход:

* `CreateUserDTO $dto` — DTO с данными нового пользователя.

Выход: —

##### edit

```php
public function edit(int $userId): UserDTO
```

Возвращает DTO с данными пользователя без применения Scope.

Вход:

* `int $userId` — ID пользователя.

Выход: `UserDTO` — данные пользователя.

##### update

```php
public function update(CreateUserDTO $dto, int $userId): void
```

Обновляет данные пользователя по его ID. Так же синхронизируя его роли с передаваемыми. Также уведомляет пользователя, о том что его роль изменилась (через `ChangeUserRole`) и его необходимо запросить свои новые разрешения.

Вход:

* `CreateUserDTO $dto` — DTO с новыми данными.
* `int $userId` — ID пользователя.

Выход: —

##### delete

```php
public function delete(User $user): MessageDTO
```

Удаляет пользователя, если это не текущий авторизованный пользователь.

Вход:

* `User $user` — модель пользователя.

Выход:

* `MessageDTO` — сообщение о результате операции.

##### prepareViewData

```php
public function prepareViewData(): UserRelatedDTO
```

Подготавливает данные для отображения формы создания/редактирования пользователя. Загружает отдел, должость, роли (при разрешении на просмотр), статусы и гендеры пользователя.

Выход:

* `UserRelatedDTO` — DTO с коллекциями отделов, должностей, ролей (если есть доступ), полов и статусов.

