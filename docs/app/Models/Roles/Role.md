# Role

Модель роли системы.
Наследует `Role` из пакета `santigarcor/laratrust`.

#### Поля

* `int $id` — идентификатор роли.
* `string $name` — уникальное системное имя роли.
* `string $display_name` — отображаемое название роли.
* `string $created_at` — дата создания записи.
* `string $updated_at` — дата обновления записи.

#### Методы

```php
protected static function booted(): void
```

Следит за обновлением роли и запрещает изменение системных ролей (перечисление `SystemRolesEnum`) по имени.

