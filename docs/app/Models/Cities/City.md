# City

Модель города.  

#### Использует:
* [Region](/app/Models/Cities/Region.md)

#### Поля

* `string $name` — название города.  
* `string $ip_start` — начальный IP-адрес.  
* `string $ip_end` — конечный IP-адрес.  
* `int $region_id` — идентификатор региона.

#### Связи

* `region()` — связь `belongsTo` с моделью [Region](/app/Models/Cities/Region.md).
