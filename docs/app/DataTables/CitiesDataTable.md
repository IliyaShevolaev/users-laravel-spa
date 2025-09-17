# CitiesDataTable

Таблица для городов.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

[CityRepositoryInterface](/app/Repositories/Interfaces/Cities/CityRepositoryInterface.md)

[City](/app/Models/Cities/City.md)

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель City:

* Изменяет поле `created_at` — формат времени на H:i d.m.Y.
* Изменяет поле `updated_at` — формат времени на H:i d.m.Y.
