<?php

namespace App\Http\Requests\Api\v1\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'priority' => ['sometimes', 'required', new Enum(TicketPriority::class)],
            'status' => ['sometimes', 'required', new Enum(TicketStatus::class)],
            'assignee_id' => 'sometimes|nullable|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
