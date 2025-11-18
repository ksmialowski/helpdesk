<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    protected function isReporter(User $user): bool
    {
        return $user->role?->name === 'reporter';
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return !$this->isReporter($user) || $ticket->assignee_id === $user->id;
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return !$this->isReporter($user) || $ticket->assignee_id === $user->id;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return !$this->isReporter($user) || $ticket->assignee_id === $user->id;
    }
}
