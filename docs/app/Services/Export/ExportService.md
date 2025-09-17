# ExportService

Сервис для управления файлами экспортов пользователей.

#### Использует:

[UserExport](/app/Models/Export/UserExport.md)

#### Методы
##### getFilePath
```php
public function getFilePath(string $fileName): string
```

Получить путь к файлу экспорта из storage по его названию.

Вход: имя файла

Выход: абсолютный путь к файлу в storage.

Ошибки: `404` — при отсутствии файла

##### markFileAsDownloaded
```php
public function markFileAsDownloaded(string $fileName): void
```

Отметить файл как скачанный пользователем. Найти запись о файле в таблице `user_exports` с заданым именем по id текущего пользователя и поле `is_user_downloaded` установить в true. Теперь пользователю не будет уведомляться о необходимости скачать этот файл. Далее метод удалет не нужный файл из storage.

Вход: имя файла

Выход: —

##### getUserMissDownloadedUserFiles
```php
public function getUserMissDownloadedUserFiles(): Collection
```

Получить список файлов экспорта по текущему пользователю из таблицы `user_exports`, которые он ещё не скачал.

Вход: —
Выход: коллекция с полями: `file_name` и `file_type`
