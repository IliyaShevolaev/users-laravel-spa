# CitySheet

Обработка листа с данными городов из Excel.
Импортирует города и привязывает их к регионам.

#### Использует:

[City](/app/Models/Cities/City.md), [Region](/app/Models/Cities/Region.md)

#### Свойства

* `regions` — коллекция всех регионов, ключированная по имени для быстрого поиска.

#### Методы

##### collection

```php
public function collection(Collection $rows)
```

Обрабатывает строки листа Excel. Проверяет обязательные поля (`name`, `ip_start`, `ip_end`, `region`) и связывает город с регионом. Удаляет дубли по диапазону IP и выполняет массовую вставку или обновление чанками через (`upsert`) в таблицу `cities`.

Вход: коллекция строк из Excel (`Collection`)

Выход: –

##### chunkSize

```php
public function chunkSize(): int
```

Возвращает размер чанка для пакетной обработки строк Excel.

Выход: число строк в excel за раз

#### Примечание

* Название столбца определяется переводом `trans('main.cities.city')`, приведенным к slug-формату.
