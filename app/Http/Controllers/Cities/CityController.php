<?php

namespace App\Http\Controllers\Cities;

use App\Http\Requests\Cities\ImportCitiesRequest;
use App\Jobs\ExportCitiesJob;
use App\Jobs\ImportCitiesJob;
use App\DTO\Cities\CreateCityDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\CitiesDataTable;
use App\Http\Controllers\Controller;
use App\Services\Cities\CityService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cities\CityRequest;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Http\Resources\Cities\CityResource;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;

class CityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private CityService $service,
        private CityRepositoryInterface $repository
    ) {
    }

    public function datatable(DataTableRequest $dataTableRequest, CitiesDataTable $datatble): JsonResponse
    {
        $this->authorize('check-permission', 'cities-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $datatble->json($dto);
    }

    public function store(CityRequest $request): void
    {
        $this->authorize('check-permission', 'cities-create');

        $dto = CreateCityDTO::from($request->validated());

        $this->service->create($dto);
    }

    public function edit(int $cityId): CityResource
    {
        $this->authorize('check-permission', 'cities-update');

        return new CityResource($this->repository->find($cityId));
    }

    public function update(CityRequest $request, int $cityId): void
    {
        $this->authorize('check-permission', 'cities-update');
        $dto = CreateCityDTO::from($request->validated());
        $this->service->update($cityId, $dto);
    }

    public function destroy(int $cityId): void
    {
        $this->authorize('check-permission', 'cities-delete');
        $this->service->delete($cityId);
    }

    public function import(ImportCitiesRequest $importCitiesRequest): void
    {
        $this->authorize('check-permission', 'cities-read');

        $this->service->storeImportFile($importCitiesRequest->file('file'));
    }

    public function export(): void
    {
        $this->authorize('check-permission', 'cities-read');

        ExportCitiesJob::dispatch(Auth::user());
    }
}
