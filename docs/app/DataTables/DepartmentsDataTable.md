# DepartmentsDataTable

Таблица для отделов пользователей.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

DepartmentRepositoryInterface

Department

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель Department:
* Изменяет поле created_at — формат времени на H:i d.m.Y.
* Изменяет поле updated_at — формат времени на H:i d.m.Y.

