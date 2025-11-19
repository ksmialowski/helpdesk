<?php

namespace Tests\Feature;

use App\Actions\Ticket\DeleteTicket;
use App\Models\Tag;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_is_deleted_and_tags_detached(): void
    {
        $ticket = Ticket::factory()->create();
        $tags = Tag::factory()->count(2)->create();
        $ticket->tags()->attach($tags->pluck('id')->toArray());

        $this->assertCount(2, $ticket->tags);

        $action = new DeleteTicket();
        $action->handle($ticket);

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
        foreach ($tags as $tag) {
            $this->assertDatabaseMissing('tag_ticket', [
                'ticket_id' => $ticket->id,
                'tag_id' => $tag->id
            ]);
        }
    }
}
