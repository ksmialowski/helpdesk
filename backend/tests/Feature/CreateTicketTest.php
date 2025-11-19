<?php

use App\Actions\Ticket\CreateTicket;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_is_created_with_tags_and_assignee(): void
    {
        $assignee = User::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $action = new CreateTicket();

        $ticket = $action->handle([
            'title' => 'Test Ticket',
            'description' => 'Some description',
            'priority' => 'high',
            'status' => 'open',
            'assignee_id' => $assignee->id,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        $this->assertDatabaseHas('tickets', ['title' => 'Test Ticket']);
        $this->assertEquals($assignee->id, $ticket->assignee_id);
        $this->assertCount(2, $ticket->tags);
    }
}
