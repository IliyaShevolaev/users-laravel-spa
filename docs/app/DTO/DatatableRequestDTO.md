# DatatableRequestDTO

DTO для передачи параметров запроса DataTable.  

#### Поля

* `?int $page` — номер страницы.  
* `?int $perPage` — количество элементов на страницу.  
* `?string $sortBy` — поле для сортировки.  
* `?string $sortOrder` — порядок сортировки (`asc` или `desc`).  
* `?string $search` — строка поиска.  
* `?int $draw` — счётчик запросов DataTable.  
