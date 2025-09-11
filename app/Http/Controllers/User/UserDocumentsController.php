<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\User\UserDocumentService;
use App\Http\Requests\User\UserDocumentRequest;
use App\DTO\User\Documents\CreateUserDocumentDTO;
use App\Http\Resources\User\UserDocumentResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Repositories\Interfaces\User\UserDocumentRepositoryInterface;

class UserDocumentsController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private UserDocumentRepositoryInterface $repository,
        private UserDocumentService $service,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return UserDocumentResource::collection($this->repository->all());
    }

    public function show(int $documentId): BinaryFileResponse
    {
        $file = $this->repository->find($documentId);

        $path = $this->service->getFilePath($file->file_name);

        return response()->download($path);
    }

    public function store(UserDocumentRequest $userDocumentRequest)
    {
        $dto = CreateUserDocumentDTO::from($userDocumentRequest->validated());

        $this->service->create($dto);
    }

    public function getByUser(int $userId): JsonResponse
    {
        $user = $this->userRepository->find($userId);

        return response()->json([
            'user' => $user,
            'documents' => UserDocumentResource::collection($user->documents)
        ]);
    }

    public function destroy(int $documentId): void
    {
        $this->authorize('check-permission', 'fileTemplates-delete');

        $document = $this->repository->find($documentId);

        $this->service->delete($document);
    }
}
