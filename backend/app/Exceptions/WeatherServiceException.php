<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class WeatherServiceException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => 'Service Unavailable',
            'message' => $this->getMessage(),
        ], 503);
    }
}
