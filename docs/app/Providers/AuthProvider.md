# AuthProvider

Провайдер для регистрации Gate и биндов политик.

#### Использует 

[SystemRolesEnum](/app/Enums/Role/SystemRolesEnum.md)

#### Gate

* `check-permission`
```php
function (User $user, string $permission)
```

Проверяет если ли у пользователя передаваемый `permission`.

* `change-user`
```php
function (User $user)
```

Проверяет может ли получать пользователь информацию о изменении/добавлении пользователя.

* `delete-user` 
```php
function (User $user, User $deleteUser)
```

Проверяет разрешение на удаление и роль удаляемого пользователя (она не должна быть system)

* `delete-role` 
```php
function (User $user, Role $role)
```

Проверяет разрешение на удаление роли и не является ли удаляемая роль системной
