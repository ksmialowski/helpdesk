<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TriageSuggestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'suggestion' => [
                'priority' => $this['priority'],
                'status' => $this['status'],
                'tags' => $this['tags'],
                'assignee_id' => $this['assignee_id'],
                'reasoning' => $this['reasoning'],
            ],
        ];
    }
}
