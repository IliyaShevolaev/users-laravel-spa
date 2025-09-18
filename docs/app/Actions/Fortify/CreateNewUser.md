# CreateNewUser

Action-класс для регистрации нового пользователя.

#### Использует:

* [User](/app/Models/User/User.md)
* [GenderEnum](/app/Enums/User/GenderEnum.md)
* [PasswordValidationRules](/app/Actions/Fortify/PasswordValidationRules.md)

#### Методы

```php
public function create(array $input): User
```

валидирует входные данные и создает нового пользователя.

#### Валидация

* `name` — обязательное строковое поле, макс. 255 символов.
* `email` — обязательное строковое поле, валидный email, макс. 255 символов, уникальное.
* `password` — обязательное поле, правила определяются через `passwordRules()`.
* `gender` — обязательное поле, проверяется на соответствие [GenderEnum](/app/Enums/User/GenderEnum.md).
