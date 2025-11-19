<?php

namespace Tests\Feature;

use App\Actions\Ticket\UpdateTicket;
use App\Models\Tag;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_is_updated_and_tags_and_status_change_are_saved(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'status' => 'open',
        ]);

        $tags = Tag::factory()->count(2)->create();

        $action = new UpdateTicket();

        $updatedTicket = $action->handle($ticket, $user, [
            'title' => 'Updated Ticket',
            'description' => 'Updated description',
            'priority' => 'high',
            'status' => 'closed',
            'assignee_id' => $assignee->id,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Updated Ticket',
            'assignee_id' => $assignee->id,
            'status' => 'closed',
        ]);

        $this->assertDatabaseHas('ticket_status_changes', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'from_status' => 'open',
            'to_status' => 'closed',
        ]);

        $this->assertCount(2, $updatedTicket->tags);
        $this->assertEqualsCanonicalizing(
            $tags->pluck('id')->toArray(),
            $updatedTicket->tags->pluck('id')->toArray()
        );
    }
}
