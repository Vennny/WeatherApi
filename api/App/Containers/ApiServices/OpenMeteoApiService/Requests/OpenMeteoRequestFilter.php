<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Requests;

use App\Containers\ApiServices\OpenMeteoApiService\Values\DTOs\OpenMeteoRequestDto;
use App\Containers\ApiServices\OpenMeteoApiService\Values\Enums\OpenMeteoLocationsEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

final class OpenMeteoRequestFilter
{
    /**
     * Fields.
     */
    final public const string FIELD_CITY = 'city';

    /**
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        private readonly ValidatorFactory $validatorFactory,
    ) {
    }

    /**
     * Get values for model.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Values\DTOs\OpenMeteoRequestDto
     */
    public function getValidatedData(Request $request): OpenMeteoRequestDto {
        $fields = $this->validate($request);
        $rawData = $request->only($fields);
        return new OpenMeteoRequestDto($rawData[self::FIELD_CITY]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string[]
     */
    public function validate(Request $request): array
    {
        $rules = $this->getRules($request);
        $validator = $this->validatorFactory->make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return \array_keys($rules);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed[]
     */
    private function getRules(Request $request): array
    {
        return [
            self::FIELD_CITY => [
                'required',
                static function (string $attribute, mixed $value, Closure $fail): void {
                    $normalized = OpenMeteoLocationsEnum::normalizeValue($value);
                    if (! OpenMeteoLocationsEnum::tryFrom($normalized)) {
                        $fail('open_meteo.validation_errors.city_not_in_enum')->translate();
                    }
                },
            ],
        ];
    }
}
