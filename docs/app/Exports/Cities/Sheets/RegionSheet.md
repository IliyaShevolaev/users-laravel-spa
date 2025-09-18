# RegionSheet

Лист Excel с данными о регионах.

#### Использует:

[Region](/app/Models/Cities/Region.md)

#### Методы

##### collection

```php
public function collection(): Collection
```

Получение коллекции регионов для экспорта, сортировка по `id`.

Выход: коллекция `Region`

##### headings

```php
public function headings(): array
```

Заголовки столбцов листа.

Выход:

* `ID` — ID региона
* `region` — название региона
* `created` — дата создания
* `updated` — дата обновления


##### map

```php
public function map($region): array
```

Преобразует объект региона в массив для строки Excel.

Выход: массив:

* `id` — ID региона
* `name` — название региона
* `created_at` — дата создания
* `updated_at` — дата обновления

##### title

```php
public function title(): string
```

Название листа в Excel.

