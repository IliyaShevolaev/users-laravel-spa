# RegionsDataTable

Таблица для регионов.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

[RegionRepositoryInterface](/app/Repositories/Interfaces/Cities/RegionRepositoryInterface.md)

[Region](/app/Models/Cities/Region.md)

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель Region:
* Изменяет поле created_at — формат времени на H:i d.m.Y.
* Изменяет поле updated_at — формат времени на H:i d.m.Y
