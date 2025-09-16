# ExportCitiesJob

Очередь для экспорта списка городов в Excel-файл.
После завершения создаёт запись в таблице user_exports и отправляет событие о готовности файла.

#### Использует:

User

[UserExport](/app/Models/Export/UserExport.md)

[ReadyExportFileEvent](/app/Events/ReadyExportFileEvent.md)

#### Поля

`user` — Пользоваетль, который начал экспорт

#### Методы
##### handle
```php
public function handle(): void
```

Выполняет экспорт. Формирует имя файла (cities-by-{username}-{timestamp}.xlsx). Сохраняет Excel-файл в storage/app/exports. Для получения записи пользователем на клиенте создаёт запись в таблице user_exports. Отправляет событие `ReadyExportFileEvent` для уведомления клиента и его попытке получить запист.
