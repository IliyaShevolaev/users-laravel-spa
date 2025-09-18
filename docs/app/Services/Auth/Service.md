# Service (Auth\Service)

Сервис для регистрации.

#### Использует:

* [GenderEnum](/app/Enums/User/GenderEnum.md)

#### Методы

##### prepareRegisterData

```php
public function prepareRegisterData(): array
```

Формирует массив данных для формы регистрации.
Возвращает список полов (`genders`) в формате:

```json
{
  "genders": [
    { "text": "Мужчина", "value": "male" },
    { "text": "Женщина", "value": "female" }
  ]
}
```
