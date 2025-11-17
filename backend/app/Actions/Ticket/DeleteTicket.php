<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class DeleteTicket
{
    /**
     * Execute the action.
     */
    public function handle(Ticket $ticket): void
    {
        DB::transaction(function () use ($ticket) {
            $ticket->tags()->detach();
            $ticket->delete();
        });
    }
}
