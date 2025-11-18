<?php

namespace App\Http\Requests\Api\v1\Weather;

use App\Http\Requests\Api\v1\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class GetWeatherRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => ['required', 'string', 'min:1', 'max:169'],
        ];
    }
}
