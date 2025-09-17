# ActivityLogDataTable

Таблица для логов активности.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

[ActivityLogRepositoryInterface](/app/Repositories/Interfaces/ActivityLogs/ActivityLogRepositoryInterface.php)

#### Методы

##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создает экземпляр таблицы `EloquentDataTable`. Форматирует модель Log:
* Изменяет поле `created_at` — формат времени на d.m.Y H:i
* Изменяет поле `updated_at` — формат времени на d.m.Y H:i
* Изменяет поле `description` — на описание с помощью `LogResolver`
* Добавляет поле `causer_name` —  задает имя из модели User, который записал этот лог
