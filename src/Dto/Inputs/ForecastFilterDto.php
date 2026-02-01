<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class ForecastFilterDto
{
    #[Assert\NotNull]
    public float $latitude;

    #[Assert\NotNull]
    public float $longitude;

    private bool $hourly = true;

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
}
