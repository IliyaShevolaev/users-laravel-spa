<?php

namespace App\Http\Controllers\Files;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function get(string $fileName)
    {
        $path = storage_path("app/private/exports/{$fileName}");

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }
}
