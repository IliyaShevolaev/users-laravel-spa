# UserDocumentService

Сервис для управления пользовательскими документами.

#### Использует:

[UserDocumentRepositoryInterface](/app/Repositories/Interfaces/User/UserDocumentRepositoryInterface.md)

[CreateUserDocumentDTO](/app/DTO/User/Documents/CreateUserDocumentDTO.md)

[UserDocument](/app/Models/User/UserDocument.md)

#### Методы

##### getFilePath

```php
public function getFilePath(string $fileName): string
```

Возвращает путь к документу пользователя в storage из директории с документами. Если файл не найден — выбрасывает 404.

Вход: `string $fileName` — имя файла

Выход: `string` — путь к файлу 

##### create

```php
public function create(CreateUserDocumentDTO $dto): void
```

Создаёт новый документ пользователя. Сохраняет загруженный документ в директорию с документами.

Вход: `CreateUserDocumentDTO $dto` — DTO с данными о документе

Выход: —

##### delete

```php
public function delete(UserDocument $userDocument): void
```

Удаляет документ пользователя и связный с ним файл из хранилища:

Вход: `UserDocument $userDocument` — модель документа

Выход: —

