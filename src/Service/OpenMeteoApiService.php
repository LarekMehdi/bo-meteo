<?php

namespace App\Service;

use App\Dto\Outputs\ForecastDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenMeteoApiService
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function fetchForecast(float $lat, float $lon): ForecastDto
    {
        // Appel HTTP externe + mapping DTO
        return new ForecastDto();
    }
}
