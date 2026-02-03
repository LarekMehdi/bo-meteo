<?php

namespace App\Tests\Service;

use App\Dto\Inputs\CityFilterDto;
use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Outputs\ForecastDto;
use App\Dto\Outputs\HourlyForecastDto;
use App\Dto\Outputs\HourlyUnitForecastDto;
use App\Exception\OpenMeteoException;
use App\Service\ForecastService;
use App\Service\OpenMeteoApiService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class ForecastServiceTest extends TestCase
{
    private $openMeteoService;
    private $service;

    protected function setUp(): void
    {
        $this->openMeteoService = $this->createMock(OpenMeteoApiService::class);
        $this->service = new ForecastService($this->openMeteoService);
    }

    /** ---------------- FORECAST ---------------- */
    public function testFetchForecastSuccess(): void
    {
        $hourlyUnits = new HourlyUnitForecastDto(
            temperature2m: '°C',
            windSpeedUnit: 'km/h'
        );

        $hourly = new HourlyForecastDto(
            time: ['2026-02-03T12:00', '2026-02-03T13:00'],
            temperature2m: [5.0, 6.0],
            weatherCode: [0, 1],
            windSpeed10m: [10.0, 12.0]
        );

        $forecastDto = new ForecastDto(
            latitude: 47.32,
            longitude: 5.02,
            elevation: 35.0,
            generationtimeMs: 0.123,
            utcOffsetSeconds: 3600,
            timezone: 'Europe/Paris',
            timezoneAbbreviation: 'CET',
            hourly: $hourly,
            hourlyUnits: $hourlyUnits
        );

        $filter = new ForecastFilterDto(47.32, 5.02);

        $this->openMeteoService->expects($this->once())
            ->method('fetchForecast')
            ->with($filter)
            ->willReturn($forecastDto);

        $result = $this->service->fetchForecast($filter);

        $this->assertSame($forecastDto, $result);
    }

    public function testFetchForecastThrows(): void
    {
        $filter = new ForecastFilterDto(47.32, 5.02);

        $this->openMeteoService->expects($this->once())
            ->method('fetchForecast')
            ->with($filter)
            ->willThrowException(new \Exception('API down'));

        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('An error occured with OpenMeteo [forecast]');

        $this->service->fetchForecast($filter);
    }

    /** ---------------- CITY ---------------- */
    public function testFetchCitiesSuccess(): void
    {
        $filter = new CityFilterDto('Dijon');
        $cities = [
            ['name' => 'Dijon', 'latitude' => 47.32, 'longitude' => 5.02],
        ];

        $this->openMeteoService->expects($this->once())
            ->method('fetchCity')
            ->with($filter)
            ->willReturn($cities);

        $result = $this->service->fetchCities($filter);

        $this->assertSame($cities, $result);
    }

    public function testFetchCitiesThrows(): void
    {
        $filter = new CityFilterDto('Dijon');

        $this->openMeteoService->expects($this->once())
            ->method('fetchCity')
            ->with($filter)
            ->willThrowException(new \Exception('API down'));

        $this->expectException(OpenMeteoException::class);
        $this->expectExceptionMessage('An error occured with OpenMeteo [city]');

        $this->service->fetchCities($filter);
    }
}
