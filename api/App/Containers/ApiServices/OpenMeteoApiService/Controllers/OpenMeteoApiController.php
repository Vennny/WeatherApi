<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Controllers;

use App\Containers\ApiServices\OpenMeteoApiService\Actions\GetOpenMeteoForecastForNextSevenDaysAction;
use App\Containers\ApiServices\OpenMeteoApiService\Requests\OpenMeteoRequestFilter;
use App\Containers\ApiServices\OpenMeteoApiService\Transformers\OpenMeteoEntityApiTransformer;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Responses\ApiResponse;
use Illuminate\Http\Request;

final class OpenMeteoApiController extends ApiController
{
    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Actions\GetOpenMeteoForecastForNextSevenDaysAction $forecastAction
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Requests\OpenMeteoRequestFilter $requestFilter
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Ship\Responses\ApiResponse
     * @throws \App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function fetch(
        GetOpenMeteoForecastForNextSevenDaysAction $forecastAction,
        OpenMeteoRequestFilter $requestFilter,
        Request $request
    ): ApiResponse {
        $data = $requestFilter->getValidatedData($request);
        $entity = $forecastAction->run($data);

        return $this->objectResponse($entity, OpenMeteoEntityApiTransformer::class);
    }
}
