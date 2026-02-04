<?php

namespace App\Service;

use App\Dto\Inputs\CityFilterDto;
use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Outputs\ForecastDto;
use App\Exception\OpenMeteoException;

final class ForecastService
{
    public function __construct(private readonly OpenMeteoApiService $openMeteoService)
    {
    }

    /** FORECAST **/
    public function fetchForecast(ForecastFilterDto $filter): ForecastDto
    {
        try {
            return $this->openMeteoService->fetchForecast($filter);
        } catch (\Throwable $e) {
            throw new OpenMeteoException('An error occurred with OpenMeteo [forecast]', $e);
        }
    }

    /** CITY **/
    public function fetchCities(CityFilterDto $filter): array
    {
        try {
            return $this->openMeteoService->fetchCity($filter);
        } catch (\Throwable $e) {
            throw new OpenMeteoException('An error occured with OpenMeteo [city]', $e);
        }
    }
}
