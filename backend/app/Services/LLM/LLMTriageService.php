<?php

namespace App\Services\LLM;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;

class LLMTriageService
{
    public function suggest(Ticket $ticket): array
    {
        $priority = TicketPriority::cases()[array_rand(TicketPriority::cases())]->value;
        $status = TicketStatus::cases()[array_rand(TicketStatus::cases())]->value;

        $tags = ['bug', 'frontend', 'backend', 'urgent', 'performance'];
        shuffle($tags);
        $ticketTags = array_slice($tags, 0, rand(1, 3));

        $assignee = User::inRandomOrder()->first();

        return [
            'priority' => $priority,
            'status' => $status,
            'tags' => $ticketTags,
            'assignee_id' => $assignee?->id,
            'reasoning' => "Mock suggestion based on ticket title: '{$ticket->title}'",
        ];
    }
}
