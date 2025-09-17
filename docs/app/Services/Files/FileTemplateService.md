# FileTemplateService

Сервис для управления шаблонами файлов и генерация документов на их основе.

#### Использует:

[FileTemplateRepository](/app/Repositories/Files/FileTemplateRepository.md)

`UserRepository`

[GenerateTemplateFileJob](/app/Jobs/GenerateTemplateFileJob.md)

[CreateFileTemplateDTO](/app/DTO/Files/Templates/CreateFileTemplateDTO.md)

[FileTemplate](/app/Models/Files/FileTemplate.md)

#### Методы
##### create
```php
public function create(CreateFileTemplateDTO $dto): void
```

Создает новый шаблон файла. Загружает файл в storage (storage/app/templates).

Вход: `CreateFileTemplateDTO` DTO с информацией о новом шаблоне

Выход: -

##### update
```php
public function update(FileTemplate $fileTemplate, CreateFileTemplateDTO $dto): void
```

Обновляет шаблон файла. Если передан новый файл — удаляет старый и сохраняет новый.

Вход: `FileTemplate` модель файла для обновления, `CreateFileTemplateDTO` DTO с информацией об обновленнном шаблоне

Выход: -

##### delete
```php
public function delete(FileTemplate $fileTemplate): void
```

Удаляет шаблон файла и связанный файл из хранилища.

Вход: `FileTemplate` модель файла для удаления

Выход: -

##### generateDocument
```php
public function generateDocument(GenerateFileDTO $generateFileDTO): void
```

Получает информацию на основе переданных данных шаблона. Отправляет задачу в очередь (`GenerateTemplateFileJob`) для генерации документа.

Вход: `GenerateFileDTO` DTO с данными о шаблоне

Выход: -

##### getTemplatePath
```php
public function getTemplatePath(string $fileName): string
```

Возвращает абсолютный путь до файла шаблона. Если файл отсутствует в storage/app/private, выбрасывает 404.

Вход: Имя файла

Выход: Абсолютный путь к файлу
