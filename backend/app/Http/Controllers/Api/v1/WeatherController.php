<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\WeatherServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Weather\GetWeatherRequest;
use App\Services\Weather\WeatherApiMeteoService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherApiMeteoService $weatherService
    ) {}

    /**
     * @throws WeatherServiceException
     */
    public function weather(GetWeatherRequest $request): JsonResponse
    {
        $weather = $this->weatherService->getCurrentWeatherByCityName($request->validated('city'));

        return response()->json([
            'success' => true,
            'data' => $weather,
        ]);
    }
}
