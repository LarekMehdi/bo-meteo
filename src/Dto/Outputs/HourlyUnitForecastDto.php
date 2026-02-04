<?php

namespace App\Dto\Outputs;

class HourlyUnitForecastDto
{
    private string $temperature2m;

    private ?string $windSpeedUnit;

    public function __construct(string $temperature2m, ?string $windSpeedUnit = null)
    {
        $this->temperature2m = $temperature2m;
        $this->windSpeedUnit = $windSpeedUnit ?? '';
    }

    public function getTemperature2m(): string
    {
        return $this->temperature2m;
    }

    public function setTemperature2m(string $temperature2m): static
    {
        $this->temperature2m = $temperature2m;

        return $this;
    }

    public function getWindSpeedUnit(): ?string
    {
        return $this->windSpeedUnit;
    }

    public function setWindSpeedUnit(?string $windSpeedUnit): static
    {
        $this->windSpeedUnit = $windSpeedUnit ?? '';

        return $this;
    }
}
