<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Transporters;

final readonly class OpenMeteoCoordinatesTransporter
{
    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        private float $latitude,
        private float $longitude,
    ) {
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
