<?php

namespace App\Service;

use App\Dto\Inputs\CityFilterDto;
use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Outputs\ForecastDto;
use App\Utils\UtilMapper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenMeteoApiService
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /** FORECAST **/
    public function fetchForecast(ForecastFilterDto $filter): ForecastDto
    {
        // variables horaires
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

        // query
        $query = [
            'latitude' => $filter->getLatitude(),
            'longitude' => $filter->getLongitude(),
        ];

        if (!empty($hourly)) {
            $query['hourly'] = implode(',', $hourly);
        }

        if ($filter->getWindSpeedUnit()) {
            $query['windspeed_unit'] = $filter->getWindSpeedUnit();
        }

        $response = $this->client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => $query,
        ]);

        $data = $response->toArray();

        return UtilMapper::mapOpenMeteoApiForecastToForecastDto($data);
    }

    /** CITY **/
    public function fetchCity(CityFilterDto $filter): array
    {
        $response = $this->client->request('GET', 'https://geocoding-api.open-meteo.com/v1/search', [
            'query' => [
                'name' => $filter->getName(),
            ],
        ]);

        $data = $response->toArray();

        return UtilMapper::mapOpenMeteoApiCityToCityDtos($data);
    }
}
