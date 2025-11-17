<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketStatusChange;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateTicket
{
    /**
     * Execute the action.
     */
    public function handle(Ticket $ticket, User $user, array $attributes): Ticket
    {
        return DB::transaction(function () use ($ticket, $user, $attributes) {
            $ticket->update([
                'title' => $attributes['title'],
                'description' => $attributes['description'] ?? null,
                'priority' => $attributes['priority'],
                'status' => $attributes['status'],
                'assignee_id' => $attributes['assignee_id'] ?? null,
            ]);

            if ($ticket->isDirty('status')) {
                TicketStatusChange::query()
                    ->create([
                        'ticket_id' => $ticket->getKey(),
                        'user_id' => $user->getKey(),
                        'from_status' => $ticket->getOriginal('status'),
                        'to_status' => $ticket->status->name,
                    ]);
            }

            if (isset($attributes['tags'])) {
                $ticket->tags()->sync($attributes['tags']);
            }

            return $ticket->fresh(['assignee', 'tags']);
        });
    }
}
