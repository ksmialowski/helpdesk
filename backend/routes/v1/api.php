<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/triage-suggest', [TicketTriageController::class, 'suggest']);
    Route::get('/external-data', [WeatherController::class, 'weather']);
});

