<?php

namespace App\Dto\Outputs;

class HourlyForecastDto
{
    /** @var string[] */
    private array $time;

    /** @var float[] */
    private array $temperature2m;

    /** @var int[] */
    private array $weatherCode;

    /** @var float[]|null */
    private ?array $windSpeed10m;

    public function __construct(
        array $time,
        array $temperature2m,
        array $weatherCode,
        ?array $windSpeed10m = null,
    ) {
        $this->time = $time;
        $this->temperature2m = $temperature2m;
        $this->weatherCode = $weatherCode;
        $this->windSpeed10m = $windSpeed10m ?? [];
    }

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

    public function getWeatherCode(): array
    {
        return $this->weatherCode;
    }

    public function setWeatherCode(array $weatherCode): static
    {
        $this->weatherCode = $weatherCode;

        return $this;
    }

    public function getWindSpeed10m(): ?array
    {
        return $this->windSpeed10m;
    }

    public function setWindSpeed10m(?array $windSpeed10m): static
    {
        $this->windSpeed10m = $windSpeed10m ?? [];

        return $this;
    }
}
