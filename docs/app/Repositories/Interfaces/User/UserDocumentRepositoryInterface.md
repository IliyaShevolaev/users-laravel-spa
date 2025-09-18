# UserDocumentRepositoryInterface

Интерфейс репозитория для работы с документами пользователей.

#### Использует:

[UserDocument](/app/Models/User/UserDocument.md)

[CreateUserDocumentDTO](/app/DTO/User/Documents/CreateUserDocumentDTO.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех документов пользователей.

Выход: коллекция `UserDocument`.

##### find

```php
public function find(int $documentId): UserDocument
```

Поиск документа по ID.

Вход: ID документа

Выход: модель `UserDocument`.

##### create

```php
public function create(CreateUserDocumentDTO $dto): void
```

Создание нового документа пользователя.

Вход: `CreateUserDocumentDTO`

Выход: —

##### delete

```php
public function delete(UserDocument $userDocument): void
```

Удаление документа пользователя.

Вход: модель `UserDocument`

Выход: —

##### getQuery

```php
public function getQuery(): Builder
```

Возвращает построитель запросов для модели `UserDocument`.

Выход: `Builder<UserDocument>`
