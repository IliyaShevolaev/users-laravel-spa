# ExportController

Контроллер для работы с файлами экспортов пользователей.

#### Использует:

[ExportService](/APP/Services/Export/ExportService.md)

#### Методы

##### get
```php
public function get(string $fileName): BinaryFileResponse
```

Получить файл экспорта (из папки exports в storage) для скачивания по его названию.

Вход: имя файла 

Выход: файл для скачивания

##### mark
```php
public function mark(string $fileName): void
```

Отметить файл как скачанный пользователем.

Вход: имя файла

Выход: —

##### getFiles
```php
public function getFiles(): JsonResponse
```

Получить список файлов экспорта, которые пользователь ещё не скачал.

Вход: —

Выход: 
```json
    {
        "file_name": "name.xlsx",
        "file_type": "xlsx",
    }
```
