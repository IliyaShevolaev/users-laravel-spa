<?php

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class EventViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        $pivot = $this->users->firstWhere('id', $user->id)?->pivot;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'description' => $this->description,
            'is_done' => $pivot->is_done,
            'end_time' => $pivot?->end_time,
            'creator' => $this->creator,
            'change_permission' => $this->canUserChange($user),
            'assigned_users' => $this->users
        ];
    }
}
