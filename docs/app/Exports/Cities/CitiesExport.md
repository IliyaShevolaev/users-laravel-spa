# CitiesExport

Экспорт данных о городах и регионах в Excel.
Содержит несколько листов: 1 — города, 2 — их регионы.

#### Использует:

[CitySheet](/app/Exports/Cities/Sheets/CitySheet.md), [RegionSheet](/app/Exports/Cities/Sheets/RegionSheet.md)

#### Методы

##### sheets

```php
public function sheets(): array
```

Возвращает массив Excel листов для экспорта.

Вход: –

Выход: массив объектов листов (`CitySheet`, `RegionSheet`)

