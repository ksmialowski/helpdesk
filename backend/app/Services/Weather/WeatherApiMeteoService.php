<?php

namespace App\Services\Weather;

use App\Exceptions\WeatherServiceException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherApiMeteoService
{
    private const GEOCODING_URL = 'https://geocoding-api.open-meteo.com/v1/search';
    private const WEATHER_URL = 'https://api.open-meteo.com/v1/forecast';
    private const CACHE_TTL = 600;

    private const WEATHER_CODES = [
        0 => 'Clear sky',
        1 => 'Mainly clear',
        2 => 'Partly cloudy',
        3 => 'Overcast',
        45 => 'Fog',
        48 => 'Depositing rime fog',
        51 => 'Drizzle (light)',
        53 => 'Drizzle (moderate)',
        55 => 'Drizzle (dense)',
        56 => 'Freezing drizzle (light)',
        57 => 'Freezing drizzle (dense)',
        61 => 'Rain (slight)',
        63 => 'Rain (moderate)',
        65 => 'Rain (heavy)',
        66 => 'Freezing rain (light)',
        67 => 'Freezing rain (heavy)',
        71 => 'Snow fall (slight)',
        73 => 'Snow fall (moderate)',
        75 => 'Snow fall (heavy)',
        77 => 'Snow grains',
        80 => 'Rain showers (slight)',
        81 => 'Rain showers (moderate)',
        82 => 'Rain showers (violent)',
        85 => 'Snow showers (slight)',
        86 => 'Snow showers (heavy)',
        91 => 'Thunderstorm (slight or moderate)',
        96 => 'Thunderstorm with hail (slight)',
        99 => 'Thunderstorm with hail (heavy)',
    ];

    public function getCurrentWeatherByCityName(string $city): array
    {
        return Cache::remember($this->getCacheKey($city), self::CACHE_TTL, function () use ($city) {
            $coordinates = $this->getCityCoordinates($city);

            return $this->getCurrentWeather(
                $coordinates['latitude'],
                $coordinates['longitude']
            );
        });
    }

    /**
     * @throws WeatherServiceException
     */
    private function getCityCoordinates(string $city): array
    {
        $response = Http::get(self::GEOCODING_URL, [
            'name' => $city,
            'count' => 1,
            'language' => 'en',
            'format' => 'json',
        ]);

        $this->validateResponse($response, 'Geocoding API request failed');

        $data = $response->json();

        if (empty($data['results'])) {
            throw new WeatherServiceException("No coordinates found for city: {$city}");
        }

        $result = $data['results'][0];

        if (!isset($result['latitude'], $result['longitude'])) {
            throw new WeatherServiceException('Invalid coordinate data received');
        }

        return [
            'latitude' => $result['latitude'],
            'longitude' => $result['longitude'],
        ];
    }

    /**
     * @throws WeatherServiceException
     */
    private function getCurrentWeather(float $latitude, float $longitude): array
    {
        $response = Http::get(self::WEATHER_URL, [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,apparent_temperature,weather_code',
        ]);

        $this->validateResponse($response, 'Weather API request failed');

        $data = $response->json();
        $current = $data['current'] ?? [];

        return [
            'temperature' => $current['temperature_2m'] ?? null,
            'apparent_temperature' => $current['apparent_temperature'] ?? null,
            'description' => $this->getWeatherDescription($current['weather_code'] ?? null),
        ];
    }

    /**
     * @throws WeatherServiceException
     */
    private function validateResponse(Response $response, string $message): void
    {
        if ($response->failed()) {
            throw new WeatherServiceException(
                "{$message}: HTTP {$response->status()}"
            );
        }

        $data = $response->json();

        if (isset($data['error'])) {
            throw new WeatherServiceException(
                $data['error']['message'] ?? $data['reason'] ?? 'Unknown API error'
            );
        }
    }

    private function getWeatherDescription(?int $weatherCode): string
    {
        return self::WEATHER_CODES[$weatherCode] ?? 'Unknown weather code';
    }

    private function getCacheKey(string $city): string
    {
        return 'weather:current:' . strtolower(trim($city));
    }
}
