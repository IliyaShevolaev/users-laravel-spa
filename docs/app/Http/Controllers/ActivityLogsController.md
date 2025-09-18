# ActivityLogsController

Контроллер для работы с журналами активности пользователей.

#### Использует:

* [DataTableRequest](/app/Http/Requests/DataTableRequest.md)
* [DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)
* [ActivityLogDataTable](/app/DataTables/ActivityLogDataTable.md)

#### Методы

##### datatable

```php
public function datatable(
    int $userId,
    DataTableRequest $dataTableRequest,
    ActivityLogDataTable $logDataTable
): JsonResponse
```

Получение списка логов активности для конкретного пользователя в формате DataTable.

Разрешения: `users-logs`

Вход:

* `userId` — ID пользователя, для которого нужно получить логи.
* `DataTableRequest` — параметры фильтрации/сортировки.

Выход:
JSON-ответ с данными таблицы логов.
