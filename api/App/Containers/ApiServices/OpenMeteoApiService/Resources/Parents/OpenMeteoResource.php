<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Resources\Parents;

use Illuminate\Support\Collection;

abstract class OpenMeteoResource
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $data;

    /**
     * Resource constructor.
     *
     * @param \Illuminate\Support\Collection|array<string, mixed> $data
     */
    public function __construct(Collection | array $data)
    {
        if ($data instanceof Collection) {
            $this->data = $data;
        } else {
            $this->data = Collection::make($data);
        }
    }

    /**
     * @param string $attribute
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getAttributeValue(string $attribute, mixed $default = null): mixed
    {
        $data = $this->data->get($attribute, $default);
        return $data !== '' ? $data : null;
    }
}
