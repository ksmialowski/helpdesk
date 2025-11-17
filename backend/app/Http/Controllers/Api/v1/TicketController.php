<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Ticket\CreateTicket;
use App\Actions\Ticket\DeleteTicket;
use App\Actions\Ticket\UpdateTicket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Ticket\IndexTicketRequest;
use App\Http\Requests\Api\v1\Ticket\StoreTicketRequest;
use App\Http\Requests\Api\v1\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(IndexTicketRequest $request): AnonymousResourceCollection
    {
        $tickets = Ticket::query()
            ->with(['assignee', 'tags'])
            ->when($request->validated('status'), fn($q, $status) => $q->status($status))
            ->when($request->validated('priority'), fn($q, $priority) => $q->priority($priority))
            ->when($request->validated('tags'), fn($q, $tags) => $q->tags(explode(',', $tags)))
            ->latest()
            ->paginate($request->validated('per_page', 15));

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request, CreateTicket $action): TicketResource
    {
        return TicketResource::make($action->handle($request->validated()));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket, UpdateTicket $action): TicketResource
    {
        return TicketResource::make($action->handle($ticket, Auth::user(), $request->validated()));
    }

    public function show(Ticket $ticket): TicketResource
    {
        return TicketResource::make($ticket->load(['assignee','tags']));
    }

    public function destroy(Ticket $ticket, DeleteTicket $action): Response
    {
        $action->handle($ticket);

        return response()->noContent();
    }
}
