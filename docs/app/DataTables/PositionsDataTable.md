# PositionsDataTable

Таблица для должностей пользователей.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

`PositionRepositoryInterface`

`Position`

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель Position:
* Изменяет поле created_at — формат времени на d.m.Y H:i.
* Изменяет поле updated_at — формат времени на d.m.Y H:i.
