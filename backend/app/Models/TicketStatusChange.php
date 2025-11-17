<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusChange extends Model
{
    protected $table = 'ticket_status_changes';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'from_status',
        'to_status',
    ];

    protected $casts = [
        'from_status' => TicketStatus::class,
        'to_status' => TicketStatus::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
