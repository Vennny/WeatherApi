<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Queries\Endpoints;

use App\Containers\ApiServices\OpenMeteoApiService\Queries\Parents\OpenMeteoQuery;
use App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity;
use App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService;
use App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

final class OpenMeteoForecastQuery extends OpenMeteoQuery
{
    private array $dailyVariables = [];

    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService $requestService
     * @param \Illuminate\Contracts\Config\Repository $configRepository
     */
    public function __construct(
        private readonly ConfigRepository $configRepository,
        OpenMeteoRequestService $requestService,
    ) {
        parent::__construct($requestService);
    }

    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum $location
     *
     * @return $this
     */
    public function setLocation(OpenMeteoLocationsEnum $location): self
    {
        $coords = $location->getCoordinatesTransporter();

        return $this->setLatAndLon(
            $coords->getLatitude(),
            $coords->getLongitude(),
        );
    }

    /**
     * @param float $lat
     * @param float $lon
     *
     * @return $this
     */
    public function setLatAndLon(float $lat, float $lon): self
    {
        $this->setParam('latitude', $lat);
        $this->setParam('longitude', $lon);
        return $this;
    }

    /**
     * @param int $days
     *
     * @return $this
     */
    public function setForecastDays(int $days): self
    {
        $this->setParam('forecast_days', $days);
        return $this;
    }

    /**
     * @param \Carbon\CarbonImmutable $start
     * @param \Carbon\CarbonImmutable $end
     *
     * @return $this
     */
    public function setTimeInterval(CarbonImmutable $start, CarbonImmutable $end): self
    {
        $this->setParam('start_date', $start->toDateString());
        $this->setParam('end_date', $end->toDateString());
        return $this;
    }

    /**
     * @return $this
     */
    public function addTemperature2mMinDailyVariable(): self
    {
        $this->dailyVariables[] = 'temperature_2m_min';
        return $this;
    }

    /**
     * @return $this
     */
    public function addTemperature2mMaxDailyVariable(): self
    {
        $this->dailyVariables[] = 'temperature_2m_max';
        return $this;
    }

    /**
     * @return $this
     */
    public function setCelsiusTemperatureUnit(): self
    {
        $this->setParam('temperature_unit', 'celsius');
        return $this;
    }

    /**
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity
     * @throws \App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException
     */
    public function get(): OpenMeteoEntity
    {
        $this->setParam('timezone', $this->configRepository->get('api_services.open_meteo.timezone', 'Europe/Prague'));

        if (! empty($this->dailyVariables)) {
            $this->setParam('daily', \implode(',', $this->dailyVariables));
        }

        return new OpenMeteoEntity($this->getData());
    }

    /**
     * @return string
     */
    protected function getSlug(): string
    {
        return "/v1/forecast/";
    }
}
