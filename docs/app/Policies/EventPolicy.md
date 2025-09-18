# EventPolicy

Класс политики для задач. Проверяет может ли пользователь назначать других пользователей на задачи или изменять задачи назначенные ему.

#### Использует

[User](/app/Models/User.md), [](/app/Models/Tasks/Event.md)
[CreateEventDTO](/app/DTO/Tasks/Event/CreateEventDTO.md)
[SystemRolesEnum](/app/Enums/Role/SystemRolesEnum.md)

#### Методы
##### store
```php
public function store(User $user, CreateEventDTO $createEventDTO): bool
```

Разрешения: `tasks-create`

Вход: Модель пользователя + данные о новом событии

Проверяет может ли роль пользователя назначать задачи передаваемым в DTO пользователям.

##### update
```php
public function update(User $user, Event $event): bool
```

Разрешения: `tasks-update`

Вход: Модель пользователя + модель обновляемого события

Проверяет может ли роль пользователя менять обновляемое им событие.

##### delete
```php
public function delete(User $user, Event $event): bool
```

Разрешения: `tasks-delete`

Вход: Модель пользователя + модель удаляемого события

Проверяет может ли роль пользователя удалять удаляемое им событие.
