# ImageRepositoryInterface

Интерфейс репозитория для работы с изображениями в галерее.

#### Использует:

[Image](/app/Models/Gallery/Image.md)
[CreateImageDTO](/app/DTO/Image/CreateImageDTO.md)

#### Методы

##### all

```php
public function all(): Collection
```

Получение всех изображений.

Выход: коллекция `Image`.

##### find

```php
public function find(int $imageId): Image
```

Поиск изображения по ID.

Вход: ID изображения

Выход: модель `Image`.

##### create

```php
public function create(CreateImageDTO $dto): Image
```

Создание нового изображения.

Вход: `CreateImageDTO`

Выход: созданная модель `Image`.

##### update

```php
public function update(Image $image, CreateImageDTO $dto): void
```

Обновление данных изображения.

Вход: модель `Image`, `CreateImageDTO`

Выход: –

##### delete

```php
public function delete(Image $image): void
```

Удаление изображения.

Вход: модель `Image`

Выход: –
