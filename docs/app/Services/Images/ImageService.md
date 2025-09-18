# ImageService

Сервис для управления изображениями.

#### Использует:

[ImageRepositoryInterface](/app/Repositories/Interfaces/Images/ImageRepositoryInterface.md)

[CreateImageDTO](/app/DTO/Image/CreateImageDTO.md)

[Image](/app/Models/Gallery/Image.md)

#### Методы

##### create

```php
public function create(CreateImageDTO $dto): void
```

Создание нового изображения и сохранение файла в библиотеку изображений модели.

Вход: `CreateImageDTO` — DTO с данными изображения.

Выход: –

##### update

```php
public function update(CreateImageDTO $dto, Image $image): void
```

Обновление существующего изображения.
Если передан новый файл, старая библиотека изображений модели очищается, и сохраняется новое изображение.

Вход: `CreateImageDTO` — DTO с обновлёнными данными изображения + `Image` — модель изображения.

Выход: –

##### delete

```php
public function delete(Image $image): void
```
Удаление изображения и связанных файлов из библиотеки изображений.

Вход: `Image` — модель изображения.

Выход: –

