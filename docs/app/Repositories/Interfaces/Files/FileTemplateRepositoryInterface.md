# FileTemplateRepositoryInterface

Интерфейс репозитория для работы с данными файловов шаблонов.

#### Использует:

[FileTemplate](/app/Models/Files/FileTemplate.md)

#### Методы
##### all
```php
public function all(): Collection
```

Получение всех шаблонов файлов в виде коллекции.

Выход: коллекция `FileTemplate`.

##### find
```php
public function find(int $fileTemplateId): FileTemplate
```

Поиск шаблона файла по ID.

Вход: ID шаблона файла

Выход: модель `FileTemplate`

##### delete
```php
public function delete(FileTemplate $fileTemplate): void
```

Удаление шаблона файла.

Вход: модель `FileTemplate`

Выход: –

##### getQuery
```php
public function getQuery(): Builder
```

Получение построителя запросов для шаблонов файлов.

Выход: `Builder`
