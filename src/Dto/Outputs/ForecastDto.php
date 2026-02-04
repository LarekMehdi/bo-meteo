<?php

namespace App\Dto\Outputs;

class ForecastDto
{
    private float $latitude;
    private float $longitude;
    private float $elevation;
    private float $generationtimeMs;
    private int $utcOffsetSeconds;
    private string $timezone;
    private string $timezoneAbbreviation;
    private HourlyForecastDto $hourly;
    private HourlyUnitForecastDto $hourlyUnits;

    public function __construct(
        float $latitude,
        float $longitude,
        float $elevation,
        float $generationtimeMs,
        int $utcOffsetSeconds,
        string $timezone,
        string $timezoneAbbreviation,
        HourlyForecastDto $hourly,
        HourlyUnitForecastDto $hourlyUnits,
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->elevation = $elevation;
        $this->generationtimeMs = $generationtimeMs;
        $this->utcOffsetSeconds = $utcOffsetSeconds;
        $this->timezone = $timezone;
        $this->timezoneAbbreviation = $timezoneAbbreviation;
        $this->hourly = $hourly;
        $this->hourlyUnits = $hourlyUnits;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getElevation(): float
    {
        return $this->elevation;
    }

    public function setElevation(float $elevation): static
    {
        $this->elevation = $elevation;

        return $this;
    }

    public function getGenerationtimeMs(): float
    {
        return $this->generationtimeMs;
    }

    public function setGenerationtimeMs(float $generationtimeMs): static
    {
        $this->generationtimeMs = $generationtimeMs;

        return $this;
    }

    public function getUtcOffsetSeconds(): int
    {
        return $this->utcOffsetSeconds;
    }

    public function setUtcOffsetSeconds(int $utcOffsetSeconds): static
    {
        $this->utcOffsetSeconds = $utcOffsetSeconds;

        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getTimezoneAbbreviation(): string
    {
        return $this->timezoneAbbreviation;
    }

    public function setTimezoneAbbreviation(string $timezoneAbbreviation): static
    {
        $this->timezoneAbbreviation = $timezoneAbbreviation;

        return $this;
    }

    public function getHourly(): HourlyForecastDto
    {
        return $this->hourly;
    }

    public function setHourly(HourlyForecastDto $hourly): static
    {
        $this->hourly = $hourly;

        return $this;
    }

    public function getHourlyUnits(): HourlyUnitForecastDto
    {
        return $this->hourlyUnits;
    }

    public function setHourlyUnits(HourlyUnitForecastDto $hourlyUnits): static
    {
        $this->hourlyUnits = $hourlyUnits;

        return $this;
    }
}
