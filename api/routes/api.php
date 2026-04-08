<?php

declare(strict_types=1);

\Illuminate\Support\Facades\Route::post('weather', [\App\Containers\ApiServices\OpenMeteoApiService\Controllers\OpenMeteoApiController::class, 'fetch']);
