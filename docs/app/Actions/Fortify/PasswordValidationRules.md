# PasswordValidationRules

Трейт для добавления правил валидации паролей.

#### Методы

```php
protected function passwordRules(): array
```

Возвращает массив правил валидации пароля.

* `required` — обязателен.
* `string` — строка.
* `confirmed` — должно совпадать с полем `password_confirmation`.
* `max:255` — не длиннее 255 символов.
* `min:3` — не короче 3 символов.
