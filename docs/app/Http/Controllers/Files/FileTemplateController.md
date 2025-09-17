# FileTemplateController

Контроллер для управления шаблонами файлов.
Реализует CRUD-операции для шаблонов файлов и генерацию документов на их основе.

#### Использует:

[CreateFileTemplateRequest](/app/Http/Requests/Files/Templates/CreateFileTemplateRequest.md], [GenereteFileWithTemplateRequest](/app/Http/Requests/Files/Templates/GenereteFileWithTemplateRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreateFileTemplateDTO](/app/DTO/Files/Templates/CreateFileTemplateDTO.md), [GenerateFileDTO](/app/DTO/Files/Templates/GenerateFileDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[FileTemplateService](/app//Services//Files/FileTemplateService.md)

FileTemplatesDataTable

[FileTemplateRepository](/app/Repositories/Files/FileTemplateRepository.md)

[FileTemplateResource](/app/Http/Resources/Files/FileTemplateResource.md)

#### Методы
##### index
```php
public function index(): AnonymousResourceCollection
```

Получение списка всех шаблонов в виде коллекции с `FileTemplateResource` внутри.

Разрешения: fileTemplates-read

Вход: –

Выход: FileTemplateResource::collection

##### datatable
```php
public function datatable(DataTableRequest $dataTableRequest, FileTemplatesDataTable $datatable): JsonResponse
```

Получение списка шаблонов для DataTable с запрошенной фильтрацией и пагинацией.

Разрешения: fileTemplates-read

Вход: параметры фильтрации/сортировки (DataTableRequest)

Выход: JSON-ответ с данными таблицы

##### store
```php
public function store(CreateFileTemplateRequest $request): void
```

Создание нового шаблона файла и дальнейшее сохранение шаблона в storage.

Разрешения: fileTemplates-create

Вход: `CreateFileTemplateRequest`

Выход: –

##### show
```php
public function show(int $templateId): BinaryFileResponse
```

Поиск шаблона по его ID, отдает файл из storage для скачивания.

Разрешения: fileTemplates-read

Вход: ID шаблона

Выход: BinaryFileResponse

##### edit
```php
public function edit(int $templateId): FileTemplateResource
```

Получение данных шаблона для его редактирования.

Разрешения: fileTemplates-update

Вход: ID шаблона

Выход: `FileTemplateResource`

##### update
```php
public function update(CreateFileTemplateRequest $request, int $id): void
```

Обновление шаблона файла по его ID с новыми данными из запроса.

Разрешения: fileTemplates-update

Вход: ID шаблона, `CreateFileTemplateRequest`

Выход: –

##### destroy
```php
public function destroy(int $id): void
```

Удаление шаблона файла по его ID, а так же удаление файла из storage.

Разрешения: fileTemplates-delete

Вход: ID шаблона

Выход: –

##### generateDocument
```php
public function generateDocument(GenereteFileWithTemplateRequest $request): void
```

Старт генерации документа на основе шаблона передаваемого в запросе.

Разрешения: fileTemplates-read

Вход: `GenereteFileWithTemplateRequest`

Выход: –
