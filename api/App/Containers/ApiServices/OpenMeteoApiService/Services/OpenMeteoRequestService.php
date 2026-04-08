<?php

declare(strict_types=1);

namespace App\Containers\ApiServices\OpenMeteoApiService\Services;

use App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException;
use App\Containers\ApiServices\OpenMeteoApiService\Queries\Endpoints\OpenMeteoForecastQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;

/**
 * Class AutoGpsRequestService
 */
final class OpenMeteoRequestService
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @param \GuzzleHttp\Client $client
     * @param \Illuminate\Contracts\Config\Repository $configRepository
     */
    public function __construct(
        private readonly Client $client,
        private readonly ConfigRepository $configRepository,
    ) {
    }

    /**
     * @return \App\Containers\ApiServices\OpenMeteoApiService\Queries\Endpoints\OpenMeteoForecastQuery
     */
    public function openMeteoForecastQuery(): OpenMeteoForecastQuery
    {
        return new OpenMeteoForecastQuery($this->configRepository, $this);
    }

    /**
     * @param string $endPoint
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \App\Containers\ApiServices\OpenMeteoApiService\Exceptions\OpenMeteoFetchingDataException
     */
    public function getData(string $endPoint, array $params = []): Collection
    {
        try {
            $result = $this->client->get($this->getFullEndPointUrl($endPoint), [
                'query' => $params,
            ]);

            /** @var array<string, mixed> $resultBody */
            $resultBody = \json_decode((string) $result->getBody(), true, 512, JSON_THROW_ON_ERROR);
            return  Collection::make($resultBody);
        } catch (ClientException | GuzzleException | \JsonException $exception) {
            throw new OpenMeteoFetchingDataException($exception->getMessage());
        }
    }

    /**
     * @param string $endPoint
     * @return string
     */
    private function getFullEndPointUrl(string $endPoint): string
    {
        return \rtrim($this->getUrl(), '/') . '/' . \ltrim($endPoint, '/');
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        if (isset($this->url)) {
            return $this->url;
        }

        $url = $this->configRepository->get('api_services.open_meteo.url');

        if (! $url) {
            throw new \RuntimeException('No Open-Meteo API url specified.');
        }

        return $this->url = $url;
    }
}
