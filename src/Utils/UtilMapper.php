<?php

namespace App\Utils;

use App\Dto\Outputs\CityDto;
use App\Dto\Outputs\ForecastDto;
use App\Dto\Outputs\HistoryDto;
use App\Dto\Outputs\HourlyForecastDto;
use App\Dto\Outputs\HourlyUnitForecastDto;

final class UtilMapper
{
    /** FORECAST **/
    public static function mapOpenMeteoApiForecastToForecastDto(array $data): ForecastDto
    {
        $hourlyDto = new HourlyForecastDto(
            time: $data['hourly']['time'],
            temperature2m: $data['hourly']['temperature_2m'],
            weatherCode: $data['hourly']['weather_code'],
            windSpeed10m: $data['hourly']['wind_speed_10m'] ?? null
        );

        $hourlyUnitsDto = new HourlyUnitForecastDto(
            temperature2m: $data['hourly_units']['temperature_2m'],
            windSpeedUnit: $data['hourly_units']['wind_speed_10m'] ?? null
        );

        $forecastDto = new ForecastDto(
            latitude: round($data['latitude'], 2),
            longitude: round($data['longitude'], 2),
            elevation: $data['elevation'],
            generationtimeMs: $data['generationtime_ms'],
            utcOffsetSeconds: $data['utc_offset_seconds'],
            timezone: $data['timezone'],
            timezoneAbbreviation: $data['timezone_abbreviation'],
            hourly: $hourlyDto,
            hourlyUnits: $hourlyUnitsDto
        );

        return $forecastDto;
    }

    /** CITY **/
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

    /**
     * @param SearchForecastHistory[] $datas
     *
     * @return HistoryDto[]
     */
    public static function mapHistoriesToHistoryDtos(array $datas): array
    {
        $dtos = [];

        foreach ($datas as $item) {
            $dto = new HistoryDto();
            $dto->setId($item->getId());
            $dto->setUserId($item->getUser()->getId());
            $dto->setLatitude($item->getLatitude());
            $dto->setLongitude($item->getLongitude());
            $dto->setCityName($item->getCityName());
            $dto->setHourly($item->isHourly());
            $dto->setWeatherCode($item->isWeatherCode());
            $dto->setWindSpeed10m($item->isWindSpeed10m());
            $dto->setWindSpeedUnit($item->getWindSpeedUnit());
            $dto->setCreatedAt($item->getCreatedAt());

            $dtos[] = $dto;
        }

        return $dtos;
    }
}
