<?php

namespace App\Http\Resources\Files;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name
        ];
    }
}
