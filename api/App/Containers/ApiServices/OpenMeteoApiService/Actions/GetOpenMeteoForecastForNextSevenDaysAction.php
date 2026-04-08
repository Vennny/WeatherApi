<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Actions;

use App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity;
use App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService;
use App\Containers\ApiServices\OpenMeteoApiService\Values\DTOs\OpenMeteoRequestDto;
use Carbon\CarbonImmutable;

final readonly class GetOpenMeteoForecastForNextSevenDaysAction
{
    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService $requestService
     */
    public function __construct(
        private OpenMeteoRequestService $requestService
    ) {
    }

    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Values\DTOs\OpenMeteoRequestDto $dto
     *
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity
     * @throws \App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException
     */
    public function run(OpenMeteoRequestDto $dto): OpenMeteoEntity
    {
        $location = $dto->getLocation();
        $tomorrow = CarbonImmutable::tomorrow();

        $entity = $this->requestService
            ->openMeteoForecastQuery()
            ->setLocation($location)
            ->setCelsiusTemperatureUnit()
            ->setTimeInterval($tomorrow, $tomorrow->addDays(6))
            ->addTemperature2mMinDailyVariable()
            ->addTemperature2mMaxDailyVariable()
            ->get();

        $entity->setLocation($location);

        return $entity;
    }
}
