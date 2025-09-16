# RegionService

Сервис для управления регионами.  
Бизнес-логика взаимодействия с регионами.

#### Использует:

* [CreateRegionDTO](/app/DTO/Cities/Region/CreateRegionDTO.md) [MessageDTO](/app/DTO/MessageDTO.md)
* [RegionRepositoryInterface](/app/Repositories/Interfaces/Cities/RegionRepositoryInterface.md)

#### Методы

##### create

```php
public function create(CreateRegionDTO $dto): void
```

Создание нового региона.

Вход: DTO с данными региона

Выход: -

##### update
```php
public function update(int $regionId, CreateRegionDTO $dto): void
```

Обновление региона по его ID.

Вход: ID региона, DTO с данными

Выход: -

##### delete
```php
public function delete(int $regionId): MessageDTO
```

Удаление региона по его ID.
Удаление если у региона нет связанных городов. Если есть, то возвращается ошибка.

Вход: ID региона

Выход: MessageDTO
