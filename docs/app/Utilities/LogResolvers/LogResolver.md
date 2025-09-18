# LogResolver

Утилита для формирования читаемых сообщений из логов активности.

#### Методы

##### resolveLogMessage

```php
public static function resolveLogMessage(Activity $log): array
```

Формирует массив сообщений для лога активности на основе типа события (`created`, `updated`, `deleted`) и свойств лога. Проходится циклом по всем атрибутам в записи о логе и для каждого вызывает `getDefaultMessage`.

Вход:

* `$log` — объект `Activity` с данными лога.

Выход:

Массив строк с описанием изменений или действий, например


##### getDefaultMessage

```php
private static function getDefaultMessage(Activity $log, string $key, string|bool|null $new, string|bool|null $old = null): string
```

Генерирует строку сообщения для одного атрибута. Применяет к нему перевод из файла локализации по его `subject_type` в таблице активностей.

Вход:

* `$log` — объект `Activity`.
* `$key` — название поля сущности.
* `$new` — новое значение поля (может быть `string`, `bool` или `null`).
* `$old` — старое значение поля (может быть `string`, `bool` или `null`).

Выход:

Строка с сообщением о создании, изменении или удалении значения, например:

