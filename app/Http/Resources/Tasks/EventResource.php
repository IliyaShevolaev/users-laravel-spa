<?php

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'department' => $this->department,
            'all_vision' => $this->allVision,
            'is_done' => $this->isDone
        ];
    }
}
