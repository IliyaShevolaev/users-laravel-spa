# AbstractDataTable

Абстрактрый класс для таблиц с данными.

#### Использует

[DatatableRequestDTO](/app/DTO/DatatableRequestDTO.md)

#### Поля 

`QueryBuilder $builder` — построитель запросов для конкретной модели, каждый класс потомок должен передать сюда свой построитель через класс репозиторий 

#### Методы

##### dataTable
```php
abstract public function dataTable(QueryBuilder $query): EloquentDataTable;
```

Абстрактрый метод. Каждый класс потомок должен реализовать метод получения и изменения столбцов в таблице в DataTable.

Например:
```php
return (new EloquentDataTable($query))
    ->editColumn('updated_at', function (Department $department) {
        return $department->updated_at->format('H:i d.m.Y');
    })
    ->setRowId('id');
```

##### setBuilder
```php
protected function setBuilder(QueryBuilder $queryBuilder): void
```

Сеттер для билдера, задается в конструкторе потомков через репозиторий класс получая Builder

##### query
```php
public function query(DatatableRequestDTO $dto, string $findField = 'name'): QueryBuilder
```
Возвращает запрос, применяя к нему сортировку и строку поиска из DTO.

Вход: `DatatableRequestDTO` DTO с данными о поиске и сортировке и пагинации, `findField` поле по которому выполняется поиск

Выход: `QueryBuilder`

##### json
```php
public function json(DatatableRequestDTO $dto, string $findField = 'name'): \Illuminate\Http\JsonResponse
```

Возвращает Json-ответ, применяя к нему пагинацию из DTO.

Вход: `DatatableRequestDTO` DTO с данными о поиске и сортировке и пагинации, `findField` поле по которому выполняется поиск

Выход: `QueryBuilder`
