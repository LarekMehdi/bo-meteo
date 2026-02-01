<?php

namespace App\Dto\Outputs;

class HourlyForecastDto
{
    /** @var string[] */
    private array $time;

    /** @var float[] */
    private array $temperature2m;

    public function getTime(): array
    {
        return $this->time;
    }

    public function setTime(array $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getTemperature2m(): array
    {
        return $this->temperature2m;
    }

    public function setTemperature2m(array $temperature2m): static
    {
        $this->temperature2m = $temperature2m;

        return $this;
    }
}
