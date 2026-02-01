<?php

namespace App\Service;

use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Outputs\ForecastDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenMeteoApiService
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function fetchForecast(ForecastFilterDto $filter): ForecastDto
    {
        $response = $this->client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $filter->getLatitude(),
                'longitude' => $filter->getLongitude(),
                'hourly' => $filter->isHourly() ? 'temperature_2m' : null,
            ],
        ]);

        $data = $response->toArray();

        return \UtilMapper::mapOpenMeteoApiForecastToForecastDto($data);
    }
}
