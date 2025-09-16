# ImportCitiesJob

Очередь для импорта данных городов и регионов из Excel-файла.

#### Использует:

RegionExcelImport

CityExcelImport

#### Поля

`filePath` — Путь до загруженного пользователем файла в storage

#### Методы

##### handle
```php
public function handle(): void
```

Выполняет импорт данных. Импортирует регионы из Excel (через `RegionExcelImport`). Импортирует города из Excel (через `CityExcelImport`). Удаляет загруженный файл из storage.
