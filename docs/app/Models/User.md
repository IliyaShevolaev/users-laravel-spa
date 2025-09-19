# User

Модель пользователя системы.

#### Использует:

[Role](/app/Models/Roles/Role.md), [Department](/app/Models/User/Department.md), [Position](/app/Models/User/Position.md), [UserDocument](/app/Models/User/UserDocument.md), [Event](/app/Models/Tasks/Event.md), [EventUser](/app/Models/Tasks/EventUser.md)

[ActiveUserScope](/app/Models/Scopes/ActiveUserScope.md)

[GenderEnum](/app/Enums/User/GenderEnum.md), [StatusEnum](/app/Enums/User/StatusEnum.md)

#### Поля

* `int $id` — идентификатор пользователя.
* `string $name` — имя пользователя.
* `string $email` — адрес электронной почты.
* `string $password` — пароль.
* `int|null $department_id` — идентификатор отдела.
* `int|null $position_id` — идентификатор должности.
* `GenderEnum $gender` — пол пользователя.
* `StatusEnum $status` — статус пользователя.
* `string $created_at` — дата создания записи.
* `string $updated_at` — дата обновления записи.
* `?string $deleted_at` — дата удаления (SoftDeletes).

#### Связи

* `department()` — связь `hasOne` с моделью [Department](/app/Models/User/Department.md).
* `position()` — связь `hasOne` с моделью [Position](/app/Models/User/Position.md).
* `documents()` — связь `hasMany` с моделью [UserDocument](/app/Models/User/UserDocument.md).
* `events()` — связь `belongsToMany` с моделью [Event](/app/Models/Tasks/Event.md) через таблицу `event_user` (Pivot-модель [EventUser](/app/Models/Tasks/EventUser.md)), содержит доп. поля `is_done`, `end_time`.

#### Методы

```php 
public static function withoutScopeFind(int $id): User
``` 

 получить пользователя без применения глобального скоупа.

```php 
public function logAssignedRole(Role $newRole = null, ?Role $oldRole = null)
``` 

 логирование присвоения или изменения роли.

```php 
public function getActivitylogOptions()
``` 

 настройки логирования модели (через пакет `spatie/laravel-activitylog`).

```php 
public function getUserRolePermissionsCollection(): Collection
``` 

 получить коллекцию прав, назначенных роли пользователя.

```php 
public function getRoleNameAttribute(): ?string
``` 

 получить имя роли пользователя.

#### Мутаторы 

Установлен мутатор на пароль, который хэширует его при установке:

```php
set: fn(string|null $value) => $value === null ? $this->password : Hash::make($value),
```
