# CityController

Контроллер для управления городами.
CRUD-операции, а также методы импорта/экспорта данных о городах.

#### Использует:

[CityRequest](/app/Http/Requests/Cities/CityRequest.md), [ImportCitiesRequest](/app/Http/Requests/Cities/ImportCitiesRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)

[CreateCityDTO](/app/DTO/Cities/CreateCityDTO.md), [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

[CityService](/app/Services/Cities/CityService.md)

[CityRepositoryInterface](/app/Repositories/Interfaces/Cities/CityRepositoryInterface.md)

[CityResource](/app/Http//Resources/Cities/CityResource.md)

[CitiesDataTable](/app/DataTables/CitiesDataTable.md)

[ExportCitiesJob](/app/Jobs/ExportCitiesJob.md), [ImportCitiesJob](/app/Jobs/ImportCitiesJob.md)

#### Методы

##### datatable
```php
public function datatable(DataTableRequest $dataTableRequest, CitiesDataTable $datatable): JsonResponse
```

Получение списка городов для DataTable.

Разрешения: cities-read

Вход: параметры фильтрации/сортировки 

Выход: JSON-ответ с данными таблицы

##### store
```php
public function store(CityRequest $request): void
```

Создание нового города.

Разрешения: cities-create

Вход: `CityRequest`

Выход: -

##### edit
```php
public function edit(int $cityId): CityResource
```

Получение данных города для редактирования.

Разрешения: cities-update

Вход: ID города

Выход: `CityResource`

##### update
```php
public function update(CityRequest $request, int $cityId): void
```

Обновление города по его ID.

Разрешения: cities-update

Вход: ID города + `CityRequest`

Выход: -

##### destroy
```php
public function destroy(int $cityId): void
```

Удаление города по его ID.

Разрешения: cities-delete
Вход: ID города
Выход: -

##### import
```php
public function import(ImportCitiesRequest $importCitiesRequest): void
```

Импорт городов из файла.
Файл загружается и сохраняется для последующей обработки через `ImportCitiesJob`.

Разрешения: cities-read

Вход: ImportCitiesRequest с файлом (file)

Выход: -

##### export
```php
public function export(): void
```

Экспорт городов в файл.
Задача ставится в очередь `ExportCitiesJob`, результат отправляется пользователю через механизм Websocket используя `ReadyExportFileEvent`.

Разрешения: cities-read

Вход: –

Выход: -
