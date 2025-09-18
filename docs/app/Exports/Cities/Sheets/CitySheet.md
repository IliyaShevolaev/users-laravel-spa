# CitySheet

Лист Excel с данными о городах.

#### Использует:

[City](/app/Models/Cities/City.md)

#### Методы

##### collection

```php
public function collection(): Collection
```

Получение коллекции городов для экспорта. Загружает связанные регионы, сортирует по `id`.

Выход: коллекция `City`

---

##### headings

```php
public function headings(): array
```

Заголовки столбцов листа.

Выход: массив строк:

* `ID` — ID города
* `city` — название города
* `region` — название региона
* `ip_start` — начало IP диапазона
* `ip_end` — конец IP диапазона
* `created` — дата создания
* `updated` — дата обновления

---

##### map

```php
public function map($city): array
```

Преобразует объект города в массив для строки Excel.

Выход:

* `id` — ID города
* `name` — название города
* `region->name` — название региона
* `ip_start` — начало IP диапазона
* `ip_end` — конец IP диапазона
* `created_at` — дата создания
* `updated_at` — дата обновления

##### afterSheet

```php
public static function afterSheet(AfterSheet $event)
```

Настройка ссылок на регионы после генерации листа после генерации таблицы.

Каждый город получает гиперссылку на соответствующий регион на листе регионов.

Вход: событие `AfterSheet`

Выход: –

##### title

```php
public function title(): string
```

Название листа в Excel.

