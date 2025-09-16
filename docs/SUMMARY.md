# Summary

* [Introduction](README.md)

* [App](app/app.md)

    * [DataTables](app/DataTables/DataTables.md)
      * [RegionsDataTable](app/DataTables/RegionsDataTable.md)
      * [CitiesDataTable](app/DataTables/CitiesDataTable.md)

    * [DTO](app/DTO/DTO.md)
      * [Cities](app/DTO/Cities/Cities.md)
        * [CityDTO](app/DTO/Cities/CityDTO.md)
        * [CreateCityDTO](app/DTO/Cities/CreateCityDTO.md)
        * [Region](app/DTO/Cities/Region/Region.md)
          * [RegionDTO](app/DTO/Cities/Region/RegionDTO.md)
          * [CreateRegionDTO](app/DTO/Cities/Region/CreateRegionDTO.md)
    * [DatatableRequestDTO](app/DTO/DatatableRequestDTO.md)
    * [MessageDTO](app/DTO/MessageDTO.md)

  * [Http](app/Http/http.md)

    * [Controllers](app/Http/Controllers/controllers.md)
      * [ActivityLogsController](app/Http/Controllers/ActivityLogsController.md)
      * [Cities](app/Http/Controllers/Cities/Cities.md)
        * [CityController](app/Http/Controllers/Cities/CityController.md)
        * [RegionController](app/Http/Controllers/Cities/RegionController.md)

    * [Services](app/Services/Services.md)
      * [Cities](app/Services/Cities/Cities.md)
        * [CityService](app/Services/Cities/CityService.php)
        * [RegionService](app/Services/Cities/RegionService.md)

    * [Requests](app/Http/Requests/requests.md)
      * [DataTableRequest](app/Http//Requests/DataTableRequest.md)
      * [Cities](app/Http/Requests/Cities/cities.md)
        * [CityRequest](app/Http/Requests/Cities/CityRequest.md)
        * [ImportCitiesRequest](app/Http/Requests/Cities/ImportCitiesRequest.md)
        * [RegionRequest](app/Http/Requests/Cities/RegionRequest.md)


    * [Resources](app/Http/Resources/resources.md)
      * [Cities](app/Http/Resources/Cities/Cities.md)
        * [RegionResource](app/Http/Resources/Cities/RegionResource.md)
        * [CityResource](app/Http/Resources/Cities/CityResource.md)

  * [Models](app/Models/models.md)
    * [Cities](app/Models/Cities/cities.md)
      * [City](app/Models/Cities/City.md)
      * [Region](app/Models/Cities/Region.md)
    * [UserExport](app/Models/Export/UserExport.md)
  
  * [Repositories](app/Repositories/repositories.md)
    * [Cities](app/Repositories/Cities/cities.md)
      * [RegionRepository](app/Repositories/Cities/RegionRepository.md)
    * [Interfaces](app/Repositories/Interfaces/interfaces.md)
      * [Cities](app/Repositories/Interfaces/Cities/cities.md)
        * [RegionRepositoryInterface](app/Repositories/Interfaces/Cities/RegionRepositoryInterface.md)

  * [Jobs](app/Jobs/jobs.md)
    * [ImportCitiesJob](app/Jobs/ImportCitiesJob.md)
    * [ExportCitiesJob](app/Jobs/ExportCitiesJob.md)

  * [Events](app/Events/events.md)
    * [ReadyExportFileEvent](app/Events/ReadyExportFileEvent.md)
