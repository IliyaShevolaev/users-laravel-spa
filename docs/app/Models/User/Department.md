# Department

Модель отдела пользователя.

#### Использует:

[User](/app/Models/User/User.md)

#### Поля

* `int $id` — идентификатор отдела.
* `string $name` — название отдела.
* `string $created_at` — дата создания.
* `string $updated_at` — дата обновления.
* `string $deleted_at` — дата удаления (soft delete).

#### Связи

* `users()` — связь `hasMany` с моделью [User](/app/Models/User/User.md), пользователи этого отдела.
