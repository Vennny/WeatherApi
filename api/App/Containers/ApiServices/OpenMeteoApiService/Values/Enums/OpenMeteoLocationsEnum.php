<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Values\Enums;

use App\Containers\ApiServices\OpenMeteoApiService\Transporters\OpenMeteoCoordinatesTransporter;
use Illuminate\Support\Str;

enum OpenMeteoLocationsEnum: string
{
    case PRAHA = 'praha';

    case BRNO = 'brno';

    case OSTRAVA = 'ostrava';

    case OLOMOUC = 'olomouc';

    case PLZEN = 'plzen';

    case PARDUBICE = 'pardubice';

    /**
     * data from https://www.latlong.net/
     *
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Transporters\OpenMeteoCoordinatesTransporter
     */
    public function getCoordinatesTransporter(): OpenMeteoCoordinatesTransporter
    {
        return match ($this) {
            self::PRAHA => new OpenMeteoCoordinatesTransporter(50.073658, 14.418540),
            self::BRNO => new OpenMeteoCoordinatesTransporter(49.195061, 16.606836),
            self::OSTRAVA => new OpenMeteoCoordinatesTransporter(49.820923, 18.262524),
            self::OLOMOUC => new OpenMeteoCoordinatesTransporter(49.593777, 17.250879),
            self::PLZEN => new OpenMeteoCoordinatesTransporter(49.738430, 13.373637),
            self::PARDUBICE => new OpenMeteoCoordinatesTransporter(50.037830, 15.780830)
        };
    }

    /**
     * @return string
     */
    public function getFormattedName(): string
    {
        return match ($this) {
            self::PRAHA => 'Praha',
            self::BRNO => 'Brno',
            self::OSTRAVA => 'Ostrava',
            self::OLOMOUC => 'Olomouc',
            self::PLZEN => 'Plzeň',
            self::PARDUBICE => 'Pardubice'
        };
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function normalizeValue(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }
}
