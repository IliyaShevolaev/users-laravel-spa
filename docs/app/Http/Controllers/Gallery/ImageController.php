<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Resources\Images\ImageResource;
use App\Repositories\Interfaces\Images\ImageRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Gallery\Image;
use App\DTO\Image\CreateImageDTO;
use App\Http\Controllers\Controller;
use App\Services\Images\ImageService;
use App\Http\Requests\Gallery\CreateImageRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageController extends Controller
{
    public function __construct(
        private ImageService $service,
        private ImageRepositoryInterface $repository
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return ImageResource::collection($this->repository->all());
    }

    public function store(CreateImageRequest $createImageRequest): void
    {
        $dto = CreateImageDTO::from($createImageRequest->validated());

        $this->service->create($dto);
    }

    public function edit(int $imageId): ImageResource
    {
        $image = $this->repository->find($imageId);

        return new ImageResource($image);
    }

    public function update(CreateImageRequest $createImageRequest, int $imageId): void
    {
        $image = $this->repository->find($imageId);

        $dto = CreateImageDTO::from($createImageRequest->validated());

        $this->service->update($dto, $image);
    }

    public function destroy(int $imageId): void
    {
        $image = $this->repository->find($imageId);

        $this->service->delete($image);
    }
}
