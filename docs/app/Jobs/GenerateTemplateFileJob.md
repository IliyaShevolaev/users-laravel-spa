# GenerateTemplateFileJob

Очередь для генерации пользовательского документа на основе шаблона. Использует phpword для генерации файла по шаблонеу с ключами.

#### Использует:

[ExportUserDTO](/app/DTO/User/ExportUserDTO.md)

[UserExport](/app/Models/Export/UserExport.md)

[UserDocument](/app/Models/User/UserDocument.md)

[ReadyExportFileEvent](/app/Events/ReadyExportFileEvent.md)

#### Поля

`ExportUserDTO $exportUserDTO` — данные пользователя для подстановки в шаблон.

`string $templatePath` — путь до файла шаблона.

`string $fileName `— базовое имя выходного файла.

`int $userId `— ID пользователя, инициировавшего экспорт.

`string $fileFormat` — формат выходного файла.

#### Методы
##### handle
```php
public function handle(): void
```

Создаёт запись в  для истории документов пользователя.
Выполняет генерацию файла на основе шаблона. Подставляет данные из `ExportUserDTO` в шаблон используя PhPWord. Генерирует уникальное имя файла. Сохраняет файл в директорию storage/app/exports и создает заптсь `UserExport`(для уведомления пользователя об экспорте файла). Копирует файл в директорию storage/app/documents и создает запись `UserDocuments` (для хранения документов о пользователе). Отправляет событие `ReadyExportFileEvent` с данными о готовом файле.
