# ImageController

Контроллер для управления изображениями в галерее.
Реализует CRUD-операции.

#### Использует:

[CreateImageRequest](/app/Http/Requests/Gallery/CreateImageRequest.md)

[CreateImageDTO](/app/DTO/Image/CreateImageDTO.md)

[ImageService](/app/Services/Images/ImageService.md)

[ImageRepositoryInterface](/app/Repositories/Interfaces/Images/ImageRepositoryInterface.md)

[ImageResource](/app/Http/Resources/Images/ImageResource.md)

#### Методы

##### index

```php
public function index(): AnonymousResourceCollection
```

Получение списка изображений.

Разрешения: images-read

Вход: –

Выход: коллекция `ImageResource`

##### store

```php
public function store(CreateImageRequest $createImageRequest): void
```

Создание нового изображения.

Разрешения: images-create

Вход: `CreateImageRequest`

Выход: –

##### edit

```php
public function edit(int $imageId): ImageResource
```

Получение данных изображения для редактирования.

Разрешения: images-update

Вход: ID изображения

Выход: `ImageResource`

##### update

```php
public function update(CreateImageRequest $createImageRequest, int $imageId): void
```

Обновление изображения по его ID.

Разрешения: images-update

Вход: ID изображения + `CreateImageRequest`

Выход: –

##### destroy

```php
public function destroy(int $imageId): void
```

Удаление изображения по его ID.

Разрешения: images-delete

Вход: ID изображения

Выход: –

