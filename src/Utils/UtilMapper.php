<?php

use App\Dto\Outputs\CityDto;
use App\Dto\Outputs\ForecastDto;
use App\Dto\Outputs\HourlyForecastDto;
use App\Dto\Outputs\HourlyUnitForecastDto;

final class UtilMapper
{
    public static function mapOpenMeteoApiForecastToForecastDto(array $data): ForecastDto
    {
        $hourlyDto = new HourlyForecastDto();
        $hourlyDto->setTime($data['hourly']['time']);
        $hourlyDto->setTemperature2m($data['hourly']['temperature_2m']);
        $hourlyDto->setWeatherCode($data['hourly']['weather_code']);
        $hourlyDto->setWindSpeed10m($data['hourly']['wind_speed_10m']);

        $hourlyUnitsDto = new HourlyUnitForecastDto();
        $hourlyUnitsDto->setTemperature2m($data['hourly_units']['temperature_2m']);
        $hourlyUnitsDto->setWindSpeedUnit($data['hourly_units']['wind_speed_10m']);

        $forecastDto = new ForecastDto();
        $forecastDto->setLatitude(round($data['latitude'], 2));
        $forecastDto->setLongitude(round($data['longitude'], 2));
        $forecastDto->setElevation($data['elevation']);
        $forecastDto->setGenerationtimeMs($data['generationtime_ms']);
        $forecastDto->setUtcOffsetSeconds($data['utc_offset_seconds']);
        $forecastDto->setTimezone($data['timezone']);
        $forecastDto->setTimezoneAbbreviation($data['timezone_abbreviation']);
        $forecastDto->setHourly($hourlyDto);
        $forecastDto->setHourlyUnits($hourlyUnitsDto);

        return $forecastDto;
    }

    public static function mapOpenMeteoApiCityToCityDtos(array $data): array
    {
        $cities = [];

        if (!empty($data['results'])) {
            foreach ($data['results'] as $item) {
                $cities[] = new CityDto(
                    $item['id'] ?? 0,
                    $item['name'] ?? '',
                    round($item['latitude'], 2) ?? 0.0,
                    round($item['longitude'], 2) ?? 0.0,
                    $item['country'] ?? null,
                    $item['country_code'] ?? null,
                    $item['timezone'] ?? null
                );
            }
        }

        return $cities;
    }
}
