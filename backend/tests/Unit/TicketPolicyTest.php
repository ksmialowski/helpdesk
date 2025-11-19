<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Policies\TicketPolicy;
use App\Models\User;
use App\Models\Ticket;
use stdClass;

class TicketPolicyTest extends TestCase
{
    public function test_viewAny_returns_true(): void
    {
        $policy = new TicketPolicy();
        $user = new User();

        $this->assertTrue($policy->viewAny($user));
    }

    public function test_create_returns_true(): void
    {
        $policy = new TicketPolicy();
        $this->assertTrue($policy->create());
    }

    public function test_view_allows_non_reporter(): void
    {
        $policy = new TicketPolicy();

        $user = new User();
        $user->role = (object) ['name' => 'admin'];

        $ticket = new Ticket();
        $ticket->assignee_id = 123;

        $this->assertTrue($policy->view($user, $ticket));
    }

    public function test_view_allows_reporter_if_assigned(): void
    {
        $policy = new TicketPolicy();

        $user = new User();
        $user->role = (object) ['name' => 'reporter'];
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->assignee_id = 1;

        $this->assertTrue($policy->view($user, $ticket));
    }

    public function test_view_denies_reporter_if_not_assigned(): void
    {
        $policy = new TicketPolicy();

        $user = new User();
        $user->role = (object) ['name' => 'reporter'];
        $user->id = 2;

        $ticket = new Ticket();
        $ticket->assignee_id = 1;

        $this->assertFalse($policy->view($user, $ticket));
    }

    public function test_update_and_delete_behave_like_view(): void
    {
        $policy = new TicketPolicy();

        $user = new User();
        $user->role = (object) ['name' => 'reporter'];
        $user->id = 1;

        $ticket = new Ticket();
        $ticket->assignee_id = 1;

        $this->assertTrue($policy->update($user, $ticket));
        $this->assertTrue($policy->delete($user, $ticket));

        $ticket->assignee_id = 2;

        $this->assertFalse($policy->update($user, $ticket));
        $this->assertFalse($policy->delete($user, $ticket));
    }
}
