<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'assignee' => UserResource::make($this->whenLoaded('assignee')),
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority->name,
            'status' => $this->status->name,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
