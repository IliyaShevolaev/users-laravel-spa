<?php

namespace App\Http\Controllers\ActivityLogs;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\DataTables\ActivityLogDataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogsController extends Controller
{
    use AuthorizesRequests;

    public function datatable(
        int $userId,
        DataTableRequest $dataTableRequest,
        ActivityLogDataTable $logDataTable
    ): JsonResponse {
        $this->authorize('check-permission', 'users-logs');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $logDataTable->json($dto, $userId);
    }
}
