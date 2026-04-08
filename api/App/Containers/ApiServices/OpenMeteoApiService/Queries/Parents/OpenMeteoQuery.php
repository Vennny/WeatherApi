<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Queries\Parents;

use App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService;
use Illuminate\Support\Collection;

abstract class OpenMeteoQuery
{
    /**
     * @var array<string, string>
     */
    private array $params = [];

    /**
     * OpenMeteoQuery constructor.
     *
     * @param \App\Containers\ApiServices\OpenMeteoApiService\Services\OpenMeteoRequestService $requestService
     */
    public function __construct(
        private readonly OpenMeteoRequestService $requestService
    ) {
    }

    /**
     * @return mixed
     */
    abstract public function get(): mixed;

    /**
     * @return string
     */
    abstract protected function getSlug(): string;

    /**
     * @return \Illuminate\Support\Collection
     *
     * @throws \App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException
     */
    protected function getData(): Collection
    {
        return $this->requestService->getData($this->getSlug(), $this->params);
    }

    /**
     * @param string $param
     * @param mixed $value
     */
    protected function setParam(string $param, mixed $value): void
    {
        $this->params[$param] = $value;
    }
}
