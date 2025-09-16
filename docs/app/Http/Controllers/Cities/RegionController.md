# RegionController

Контроллер для управления регионами.
CRUD операции для регионов и метод получения всех регионов в формате DataTable.

#### Использует:

* [RegionRequest](/app/Http/Requests/Cities/RegionRequest.md), [DataTableRequest](/app/Http/Requests/DataTableRequest.md)
* [CreateRegionDTO](/app/DTO/Cities/Region/CreateRegionDTO.md) [MessageDTO](/app/DTO/MessageDTO.md) [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)
* [RegionService](/app/Services/Cities/RegionService.md)
* [RegionsDataTable](/app/DataTables/RegionsDataTable.md)
* [RegionRepositoryInterface](/app/Repositories/Interfaces/Cities/RegionRepositoryInterface.md)
* [RegionResource](/app/Http/Resources/Cities/RegionResource.md)

#### Методы

##### datatable

```php
public function datatable(DataTableRequest $request, RegionsDataTable $datatable): JsonResponse
```

Получение списка регионов для DataTable.

Разрешения: cities-read

Вход: параметры фильтрации/сортировки 

Выход: JSON-ответ с данными таблицы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получения списока всех регионов в виде ресурсной коллекции.

Права: cities-read

Вход: -

Выход: RegionResource::collection

##### store

```php
public function store(RegionRequest $request): JsonResponse
```

Создание нового региона.

Права: cities-create

Вход: RegionRequest

Выход: -

##### store

```php
public function edit(int $regionId): RegionResource
```

Получение данных региона для редактирования.

Права: cities-update

Вход: ID региона

Выход: RegionResource

##### update

```php
public function update(RegionRequest $request, int $regionId): void
```

Обновление региона по его ID.

Права: cities-update

Вход: ID региона + RegionRequest

Выход: -

##### destroy

```php
    public function destroy(int $regionId): JsonResponse
```

Удаление региона по его ID.

Права: cities-delete

Вход: ID региона

Выход: 
* status code — 200
```json
{
  "message": "success"
}
```

* status code — 409
```json
{
  "message": "delete not allowed"
}
```
