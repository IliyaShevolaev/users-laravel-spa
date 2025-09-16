# DataTableRequest

Request для валидации параметров запроса DataTable.  

#### Параметры запроса

* `page` — nullable, integer, min: 1 (номер страницы).  
* `per_page` — nullable, integer, min: -1, max: 1000 (количество элементов на страницу).  
* `sort_by` — nullable, string, max: 255 (поле для сортировки).  
* `sort_order` — nullable, in: `asc,desc` (порядок сортировки).  
* `search` — nullable, string, max: 255 (строка поиска).  
* `draw` — nullable, integer (счётчик запросов DataTable).

#### Подготовка данных

Перед валидацией автоматически устанавливаются значения по умолчанию:

* `page` — если не передан, устанавливается в `1`.  
* `per_page` — если не передан, устанавливается в `10`.  
* `sort_order` — приводится к нижнему регистру (`asc` или `desc`) при передаче.  
