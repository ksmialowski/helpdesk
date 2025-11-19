<?php

namespace Tests\Unit\Requests\Api\v1\Weather;

use Tests\TestCase;
use App\Http\Requests\Api\v1\Weather\GetWeatherRequest;

class GetWeatherRequestTest extends TestCase
{
    public function test_rules_contains_city_validation(): void
    {
        $request = new GetWeatherRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('city', $rules);

        $this->assertContains('required', $rules['city']);
        $this->assertContains('string', $rules['city']);
        $this->assertContains('min:1', $rules['city']);
        $this->assertContains('max:169', $rules['city']);
    }
}
