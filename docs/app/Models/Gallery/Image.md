# Image

Модель для хранения изображений в галерее.

Использует пакет spatie/laravel-medialibrary для работы с файлами изображений.

#### Поли:

`int $id` — ID.

`string $name` — название изображения.

`string $created_at` — дата создания записи.

`string $updated_at` — дата обновления записи.

`?string $deleted_at` — дата удаления (SoftDeletes).

#### Методы:
##### registerMediaConversions
```php
public function registerMediaConversions(?Media $media = null): void
```
Cоздаёт preview оригинального изображения с размерами 300x300 пикселей.
