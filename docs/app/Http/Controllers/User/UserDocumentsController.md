# UserDocumentsController

Контроллер для управления пользовательскими документами.
Реализует CRUD-операции и работу с DataTable.

#### Использует:

[UserDocumentRequest](/app/Http/Requests/User/UserDocumentRequest.md)

[CreateUserDocumentDTO](/app/DTO/User/Documents/CreateUserDocumentDTO.md), [DatatableRequestDTO](/app/DTO/DataTable/DatatableRequestDTO.md)

[UserDocumentService](/app/Services/User/UserDocumentService.md)

[UserDocumentRepositoryInterface](/app/Repositories/Interfaces/User/UserDocumentRepositoryInterface.md), [UserRepositoryInterface](/app/Repositories/Interfaces/User/UserRepositoryInterface.md)

[UserDocumentResource](/app/Http/Resources/User/UserDocumentResource.md)

[UserDocumentsDataTable](/app/DataTables/UserDocumentsDataTable.md)


#### Методы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получение списка всех документов пользователей.

Разрешения: users-read

Вход: –

Выход: коллекция `UserDocumentResource`.

---

##### datatable

```php
public function datatable(
    int $userId,
    DataTableRequest $dataTableRequest,
    UserDocumentsDataTable $datatable
): JsonResponse
```

Получение списка документов пользователя в формате DataTable.

Разрешения: users-read

Вход:

* `userId` — ID пользователя.
* `DataTableRequest` — параметры фильтрации/сортировки.

Выход: JSON-ответ с данными таблицы документов.

---

##### show

```php
public function show(int $documentId): BinaryFileResponse
```

Скачивание документа по его ID.

Разрешения: users-read

Вход: ID документа.

Выход: бинарный ответ (файл для скачивания).

---

##### store

```php
public function store(UserDocumentRequest $userDocumentRequest): void
```

Создание нового документа для пользователя.

Разрешения: users-create

Вход: `UserDocumentRequest`.

Выход: –

---

##### getByUser

```php
public function getByUser(int $userId): JsonResponse
```

Получение документов по переданному ID пользователя.

Разрешения: users-read

Вход: ID пользователя.

Выход: JSON-ответ с данными пользователя и его документами.

---

##### destroy

```php
public function destroy(int $documentId): void
```

Удаление пользовательского документа по его ID.

Разрешения: fileTemplates-delete

Вход: ID документа.
Выход: –
