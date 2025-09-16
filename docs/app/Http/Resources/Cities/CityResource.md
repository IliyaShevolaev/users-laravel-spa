# CityResource

Resource для представления данных города.

#### Использует: 

* [RegionResource](/app/Http/Resources/Cities/RegionResource.md)

#### Поля

* `int $id` — ID города.
* `string $name` — название города.
* `string $ip_start` — начальный IP-адрес.
* `string $ip_end` — конечный IP-адрес.
* `RegionResource $region` — данные региона, к которому принадлежит город.
* `string $created_at` — дата создания.
* `string $updated_at` — дата обновления.
