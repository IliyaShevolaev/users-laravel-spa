# CreateUserDTO

DTO для создания нового пользователя.

#### Использует:

[GenderEnum](/app/Enums/User/GenderEnum.md), [StatusEnum](/app/Enums/User/StatusEnum.md)

#### Поля

* `string $name` — имя пользователя.
* `string $email` — email пользователя.
* `GenderEnum $gender` — пол пользователя.
* `StatusEnum $status` — статус пользователя.
* `?string $password` — пароль пользователя, может быть `null`.
* `?int $departmentId` — id отдела пользователя, может быть `null`.
* `?int $positionId` — id должности пользователя, может быть `null`.
* `?int $role` — id роли пользователя, может быть `null`.

