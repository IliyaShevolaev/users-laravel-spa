# RegionRepositoryInterface

Интерфейс репозитория для работы с регионами.  
Определяет методы для CRUD-операций и получения данных из базы.

#### Методы

##### all

```php
public function all(): Collection
```
Получение всех регионов.

Выход: коллекция RegionDTO.

##### create
```php
public function create(CreateRegionDTO $dto): void
```

Создание нового региона.

Вход: CreateRegionDTO

Выход: -

##### update
```php
public function update(int $regionId, CreateRegionDTO $dto): void
```
Обновление региона по его ID.

Вход: ID региона + CreateRegionDTO

Выход: -

##### delete
```php
public function delete(Region $region): void
```
Удаление региона.

Вход: Модель Region

Выход: -

##### find
```php
public function find(int $regionId): Region
```
Поиск региона по ID.

Вход: ID региона

Выход: Модель Region

##### getQuery
```php
public function getQuery(): Builder
```
Получение Eloquent Builder для региона.

Выход: Builder

##### count
```php
public function count(): int
```
Подсчёт общего числа регионов.

Выход: количество регионов (int)
