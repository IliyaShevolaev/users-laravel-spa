# RolesDataTable

Таблица для ролей пользователей.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

`RoleRepositoryInterface`

`Role`

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель Role:
* Изменяет поле created_at — формат даты/времени на d.m.Y H:i.
* Изменяет поле updated_at — формат даты/времени на d.m.Y H:i.
