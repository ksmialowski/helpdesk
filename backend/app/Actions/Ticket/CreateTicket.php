<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class CreateTicket
{
    /**
     * Execute the action.
     */
    public function handle(array $attributes): Ticket
    {
        return DB::transaction(function () use ($attributes) {
            $ticket = Ticket::query()
                ->with(['assignee', 'tags'])
                ->create([
                    'title' => $attributes['title'],
                    'description' => $attributes['description'] ?? null,
                    'priority' => $attributes['priority'],
                    'status' => $attributes['status'],
                    'assignee_id' => $attributes['assignee_id'] ?? null,
                ]);

            if (isset($attributes['tags'])) {
                $ticket->tags()->attach($attributes['tags']);
            }

            return $ticket;
        });
    }
}
