<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Values\DTOs;

use App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum;
use App\Ship\Parents\DataTransferObjects\DataTransferObject;

final class OpenMeteoRequestDto extends DataTransferObject
{
    /**
     * @var \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum
     */
    private readonly OpenMeteoLocationsEnum $location;

    /**
     * @param string $location
     */
    public function __construct(string $location) {
        $this->location = OpenMeteoLocationsEnum::from(OpenMeteoLocationsEnum::normalizeValue($location));
    }

    /**
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum
     */
    public function getLocation(): OpenMeteoLocationsEnum
    {
        return $this->location;
    }
}
