<?php

namespace App\Http\Controllers\Cities;

use Illuminate\Http\Request;
use App\DTO\Cities\CreateCityDTO;
use Illuminate\Http\JsonResponse;
use App\DataTables\CitiesDataTable;
use App\Exports\Cities\CitiesExport;
use App\Http\Controllers\Controller;
use App\Services\Cities\CityService;
use Maatwebsite\Excel\Facades\Excel;
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

    public function store(CityRequest $request): JsonResponse
    {
        $this->authorize('check-permission', 'cities-create');
        debugbar()->info($request->validated());

        $dto = CreateCityDTO::from($request->validated());

        $this->service->create($dto);

        return response()->json(['message' => 'success']);
    }

    public function edit(int $cityId): CityResource
    {
        $this->authorize('check-permission', 'cities-update');
        return new CityResource($this->repository->find($cityId));
    }

    public function update(CityRequest $request, int $cityId): JsonResponse
    {
        $this->authorize('check-permission', 'cities-update');
        $dto = CreateCityDTO::from($request->validated());
        $this->service->update($cityId, $dto);

        return response()->json(['message' => 'success']);
    }

    public function destroy(int $cityId): JsonResponse
    {
        $this->authorize('check-permission', 'cities-delete');
        $this->service->delete($cityId);

        return response()->json(['message' => 'success']);
    }

    public function export()
    {
        return Excel::download(new CitiesExport, 'users.xlsx');
    }
}
