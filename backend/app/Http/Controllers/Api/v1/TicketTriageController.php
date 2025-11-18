<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TriageSuggestionResource;
use App\Models\Ticket;
use App\Services\LLM\LLMTriageService;

class TicketTriageController extends Controller
{
    public function __construct(protected LLMTriageService $llmService) {}

    public function suggest(Ticket $ticket): TriageSuggestionResource
    {
        $suggestion = $this->llmService->suggest($ticket);
        return TriageSuggestionResource::make($suggestion);
    }
}
