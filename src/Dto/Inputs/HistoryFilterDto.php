<?php

namespace App\Dto\Inputs;

class HistoryFilterDto extends GenericFilterDto
{
    public ?float $latitude = null;
    public ?float $longitude = null;

    public function getLatitude(): float
    {
        return round($this->latitude, 2);
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return round($this->longitude, 2);
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
