# UserDTO

DTO для представления данных пользователя.

* [GenderEnum](/app/Enums/User/GenderEnum.md), [StatusEnum](/app/Enums/User/StatusEnum.md), [RoleDTO](/app/DTO/Roles/RoleDTO.md)

#### Поля

* `int $id` — id пользователя.

* `string $name` — имя пользователя.

* `string $email` — почта пользователя.

* `GenderEnum $gender` — пол пользователя, использует перечисление `GenderEnum`.

* `StatusEnum $status` — статус пользователя, использует перечисление `StatusEnum`.

* `?int $departmentId` — ID отдела пользователя, может быть `null`.
* `?int $positionId` — ID должности пользователя, может быть `null`.

* `?RoleDTO $role` — роль пользователя в виде `RoleDTO`, может быть `null`.
