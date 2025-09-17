# FileTemplatesDataTable

Таблица для шаблонов файлов.

Наследуется от абстрактного класса: [AbstractDataTable](/app/DataTables/AbstractDataTable.md)

#### Использует

[FileTemplateRepositoryInterface](/app/Repositories/Interfaces/Files/FileTemplateRepositoryInterface.md)

[FileTemplate](/app/Models/Files/FileTemplate.md)

#### Методы
##### dataTable
```php
public function dataTable(QueryBuilder $query): EloquentDataTable
```

Создаёт экземпляр таблицы EloquentDataTable. Форматирует модель FileTemplate:
* Изменяет поле created_at — формат времени на H:i d.m.Y.
* Изменяет поле updated_at — формат времени на H:i d.m.Y.
