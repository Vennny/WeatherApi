<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Resources;

use App\Containers\ApiServices\OpenMeteoApiService\Resources\Parents\OpenMeteoResource;
use App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class OpenMeteoEntity extends OpenMeteoResource
{
    /**
     * Attributes
     */
    public const string OM_ATTR_DAILY = 'daily';

    public const string OM_ATTR_DAILY_TIME = 'time';

    public const string OM_ATTR_DAILY_TEMPERATURE_2_M_MIN = 'temperature_2m_min';

    public const string OM_ATTR_DAILY_TEMPERATURE_2_M_MAX = 'temperature_2m_max';

    /**
     * @var \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum
     */
    private readonly OpenMeteoLocationsEnum $location;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDaily(): Collection
    {
        return Collection::wrap($this->getAttributeValue(self::OM_ATTR_DAILY));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDailyDates(): Collection
    {
        return Collection::wrap($this->getDaily()->get(self::OM_ATTR_DAILY_TIME))
            ->transform(static fn (string $date): CarbonImmutable => CarbonImmutable::make($date));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDailyTemperature2mMin(): Collection
    {
        return Collection::wrap($this->getDaily()->get(self::OM_ATTR_DAILY_TEMPERATURE_2_M_MIN));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDailyTemperature2mMax(): Collection
    {
        return Collection::wrap($this->getDaily()->get(self::OM_ATTR_DAILY_TEMPERATURE_2_M_MAX));
    }

    /**
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum
     */
    public function getLocation(): OpenMeteoLocationsEnum
    {
        return $this->location;
    }

    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum $location
     */
    public function setLocation(OpenMeteoLocationsEnum $location): void
    {
        $this->location = $location;
    }
}
