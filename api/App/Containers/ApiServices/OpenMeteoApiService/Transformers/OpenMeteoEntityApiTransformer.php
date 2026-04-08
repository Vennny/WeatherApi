<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Transformers;

use App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity;
use App\Ship\Parents\Transformers\ApiTransformer;
use Carbon\CarbonImmutable;

final class OpenMeteoEntityApiTransformer extends ApiTransformer
{
    final public const string PROP_CITY = 'city';

    final public const string PROP_TEMPERATURE = 'temperature';

    final public const string PROP_TEMPERATURE_DATE = 'date';

    final public const string PROP_TEMPERATURE_MIN = 'min';

    final public const string PROP_TEMPERATURE_MAX = 'max';

    /**
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Resources\OpenMeteoEntity $entity
     *
     * @return mixed[]
     */
    public function transform(OpenMeteoEntity $entity): array
    {
        $dailyMinTemperatures = $entity->getDailyTemperature2mMin();
        $dailyMaxTemperatures = $entity->getDailyTemperature2mMax();

        return [
            self::PROP_CITY => $entity->getLocation()->getFormattedName(),
            self::PROP_TEMPERATURE => $entity->getDailyDates()
                ->map(function (CarbonImmutable $date, int $index) use ($dailyMinTemperatures, $dailyMaxTemperatures): array {
                    return [
                        self::PROP_TEMPERATURE_DATE => $this->formatDate($date),
                        self::PROP_TEMPERATURE_MIN => $dailyMinTemperatures[$index],
                        self::PROP_TEMPERATURE_MAX => $dailyMaxTemperatures[$index]
                    ];
                }),
        ];
    }
}
