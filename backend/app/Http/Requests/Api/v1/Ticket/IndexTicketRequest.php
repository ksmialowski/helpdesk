<?php

namespace App\Http\Requests\Api\v1\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class IndexTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', new Enum(TicketStatus::class)],
            'priority' => ['sometimes', new Enum(TicketPriority::class)],
            'tags' => 'sometimes|string|regex:/^[0-9,]+$/',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
