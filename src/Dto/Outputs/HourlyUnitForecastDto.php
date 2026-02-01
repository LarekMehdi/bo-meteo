<?php

namespace App\Dto\Outputs;

class HourlyUnitForecastDto
{
    private string $temperature2m;

    public function getTemperature2m(): string
    {
        return $this->temperature2m;
    }

    public function setTemperature2m(string $temperature2m): static
    {
        $this->temperature2m = $temperature2m;

        return $this;
    }
}
