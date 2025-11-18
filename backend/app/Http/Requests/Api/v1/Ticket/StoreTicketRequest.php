<?php

namespace App\Http\Requests\Api\v1\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Requests\Api\v1\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTicketRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => ['required', new Enum(TicketPriority::class)],
            'status' => ['required', new Enum(TicketStatus::class)],
            'assignee_id' => 'nullable|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
