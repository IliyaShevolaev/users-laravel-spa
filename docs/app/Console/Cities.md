# Cities

Команда для запуска импорта городов из внешнего API.

#### Использует:

[ParseCitiesJob](/app/Jobs/ParseCitiesJob.md)

#### Поля
`signature` — parse:cities

#### Методы
##### handle
```php
public function handle(): void
```

Берет url стороннего API из конфига и ставит задачу `ParseCitiesJob` в очередь.
