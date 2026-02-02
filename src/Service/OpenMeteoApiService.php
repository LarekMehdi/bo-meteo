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
        $hourly = [];

        if ($filter->isHourly()) {
            $hourly[] = 'temperature_2m';
        }

        if ($filter->isWeatherCode()) {
            $hourly[] = 'weather_code';
        }

        if ($filter->isWindSpeed10m()) {
            $hourly[] = 'wind_speed_10m';
        }

        $response = $this->client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $filter->getLatitude(),
                'longitude' => $filter->getLongitude(),
                'hourly' => implode(',', $hourly),
            ],
        ]);

        $data = $response->toArray();

        return \UtilMapper::mapOpenMeteoApiForecastToForecastDto($data);
    }
}
