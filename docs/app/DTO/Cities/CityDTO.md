# CityDTO

DTO для передачи данных города.  

#### Использует:
* [RegionDTO](./Region/RegionDTO.md)

#### Поля

* `int $id` — идентификатор города.  
* `string $name` — название города.  
* `string $startIp` — начальный IP-адрес.  
* `string $endIp` — конечный IP-адрес.  
* `RegionDTO $region` — данные региона, к которому принадлежит город.  
