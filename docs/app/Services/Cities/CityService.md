# CityService

Сервис для управления городами.  
Бизнес-логика взаимодействия с городами.

#### Использует:

[CityRepositoryInterface](/app/Repositories/Interfaces/Cities/CityRepositoryInterface.md)

[CreateCityDTO](/app/DTO/Cities/CreateCityDTO.md)

[ImportCitiesJob](/app/Jobs/ImportCitiesJob.md)

#### Методы

##### create
```php
public function create(CreateCityDTO $dto): void
```

Создание нового города.

Вход: `CreateCityDTO` — DTO с данными города

Выход: -

##### update
```php
public function update(int $cityId, CreateCityDTO $dto): void
```

Обновление города по его ID.

Вход: ID города, `CreateCityDTO` — DTO с обновлёнными данными

Выход: -

##### delete
```php
public function delete(int $cityId): void
```

Удаление города по ID.

Вход: ID города

Выход: –

##### storeImportFile
```php
public function storeImportFile(UploadedFile $file): void
```

Сохраняет загруженный файл импорта городов и ставит задачу ImportCitiesJob в очередь.

Вход: `UploadedFile` — загруженный Excel-файл с городами

Выход: –
