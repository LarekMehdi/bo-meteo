<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class ForecastFilterDto
{
    #[Assert\NotNull]
    public float $latitude;

    #[Assert\NotNull]
    public float $longitude;

    public bool $hourly = true;

    public bool $weatherCode = true;

    public bool $windSpeed10m = true;

    public string $windSpeedUnit = 'kmh';  // TODO: enum => ms, mph and kn

    public function __construct(float $latitude, float $longitude, bool $hourly = true)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->hourly = $hourly;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function isHourly(): bool
    {
        return $this->hourly;
    }

    public function isWeatherCode(): bool
    {
        return $this->weatherCode;
    }

    public function isWindSpeed10m(): bool
    {
        return $this->windSpeed10m;
    }

    public function getWindSpeedUnit(): string
    {
        return $this->windSpeedUnit;
    }
}
