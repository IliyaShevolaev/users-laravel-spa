<?php

namespace App\Http\Controllers\Cities;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\DataTables\RegionsDataTable;
use App\Http\Controllers\Controller;
use App\Services\Cities\RegionService;
use App\DTO\Cities\Region\CreateRegionDTO;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Http\Requests\Cities\RegionRequest;
use App\Http\Resources\Cities\RegionResource;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\Interfaces\Cities\RegionRepositoryInterface;

class RegionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private RegionService $service,
        private RegionRepositoryInterface $repository
    ) {
    }

    public function datatable(DataTableRequest $dataTableRequest, RegionsDataTable $datatble): JsonResponse
    {
        $this->authorize('check-permission', 'cities-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $datatble->json($dto);
    }

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('check-permission', 'cities-read');

        return RegionResource::collection($this->repository->all());
    }

    public function store(RegionRequest $regionRequest): void
    {
        $this->authorize('check-permission', 'cities-create');

        $dto = CreateRegionDTO::from($regionRequest->validated());

        $this->service->create($dto);
    }

    public function edit(int $regionId): RegionResource
    {
        $this->authorize('check-permission', 'cities-update');

        $regionToEdit = $this->repository->find($regionId);

        return new RegionResource($regionToEdit);
    }

    public function update(RegionRequest $regionRequest, int $regionId): void
    {
        $this->authorize('check-permission', 'cities-update');

        $dto = CreateRegionDTO::from($regionRequest->validated());

        $this->service->update($regionId, $dto);
    }

    public function destroy(int $regionId): JsonResponse
    {
        $this->authorize('check-permission', 'cities-delete');

        $deleteResult = $this->service->delete($regionId);

        return response()->json(['message' => $deleteResult->message], $deleteResult->code);
    }
}
