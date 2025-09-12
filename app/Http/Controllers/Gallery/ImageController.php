<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Resources\Images\ImageResource;
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
    public function __construct(private ImageService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return ImageResource::collection(Image::all());
    }

    public function store(CreateImageRequest $createImageRequest): void
    {
        $dto = CreateImageDTO::from($createImageRequest->validated());

        $this->service->create($dto);
    }
}
