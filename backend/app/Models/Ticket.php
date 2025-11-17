<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'assignee_id',
    ];

    protected $casts = [
        'priority' => TicketPriority::class,
        'status' => TicketStatus::class,
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', '=', $status);
    }

    public function scopePriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', '=', $priority);
    }

    public function scopeTags(Builder $query, array $tags): Builder
    {
        return $query->whereHas('tags', fn(Builder $q) => $q->whereIn('name', $tags));
    }
}
