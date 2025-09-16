# CityRepositoryInterface

Интерфейс репозитория для работы с городами.
CRUD-операций и получение данных из базы.

#### Использует:
[CityDTO](/app/DTO/Cities/CityDTO.md)

[CreateCityDTO](/app/DTO/Cities/CreateCityDTO.md)

[City](/app/Models/Cities/City.md)


#### Методы

##### all
```php
public function all(): Collection
```

Получение всех городов.

Выход: коллекция CityDTO.

##### find
```php
public function find(int $cityId): City
```

Поиск города по ID.

Вход: ID города

Выход: модель City

##### create
```php
public function create(CreateCityDTO $dto): void
```

Создание нового города.

Вход: CreateCityDTO

Выход: –

##### update
```php
public function update(int $cityId, CreateCityDTO $dto): void
```

Обновление города по его ID.

Вход: ID города, CreateCityDTO

Выход: –

##### delete
```php
public function delete(City $city): void
```

Удаление города.

Вход: модель City
Выход: –

##### getQuery
```php
public function getQuery(): Builder
```

Получение построителя запросов для городов.

Выход: Builder

##### count
```php
public function count(): int
```

Подсчёт общего числа городов.

Выход: количество городов (int)
