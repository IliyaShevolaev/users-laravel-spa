# UserRelatedDTO

DTO для представления связанных данных пользователя, включая отделы, должности, роли и справочные значения (пол, статус). Используется для обновления или создания пользователя.

#### Использует:

[UserDTO](/app/DTO/User/UserDTO.md), [DepartmentDTO](/app/DTO/User/Department/DepartmentDTO.md), [PositionDTO](/app/DTO/User/Position/PositionDTO.md), [RoleDTO](/app/DTO/Roles/RoleDTO.md)

#### Поля

* `?UserDTO $user` — данные пользователя, может быть `null`.
* `DepartmentDTO[] $departments` — список всех отделов.
* `PositionDTO[] $positions` — список всех должностей.
* `?RoleDTO[] $roles` — список ролей, может быть `null` (если у пользователя нет разрешения на просмотр ролей).
* `array $genders` — список полов пользователя.
* `array $statuses` — список статусов пользователя.
