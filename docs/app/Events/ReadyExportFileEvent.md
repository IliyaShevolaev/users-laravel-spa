# ReadyExportFileEvent

Событие, которое отправляется пользователю после успешного экспорта файла.
Транслируется через приватный канал(export.file.ready.{userId}) и уведомляет клиента о том, что файл готов к скачиванию.

#### Поля

`userId` — ID пользователя

`fileName` — имя сохранённого файла

`downloadName` — имя файла для скачивания (по умолчанию = fileName)


Выход:
```json
{
  "userId": 1,
  "fileName": "cities-by-username-123456789.xlsx",
  "downloadName": "cities.xlsx"
}
```
