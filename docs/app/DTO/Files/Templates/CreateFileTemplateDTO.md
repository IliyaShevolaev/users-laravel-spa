# CreateFileTemplateDTO

DTO для создания или обновления шаблона файла.

#### Поля

`string $name` — название шаблона.

`?UploadedFile $fileTemplate` — файл шаблона, обязательный при создании, опциональный при обновлении.

`string $filePath` — путь к сохранённому файлу (устанавливается через метод `setFilePath`).

#### Методы
```php
public function setFilePath(string $filePath): void 
```

сохраняет путь к файлу и очищает свойство $fileTemplate, чтобы работать уже с сохранённым путем (`filePath`).
