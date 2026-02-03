<?php

namespace App\Dto\Outputs;

class HistoryDto
{
    private int $id;
    private int $userId;
    private float $latitude;
    private float $longitude;
    private bool $hourly;
    private bool $weatherCode;
    private bool $windSpeed10m;
    private string $windSpeedUnit;
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function isHourly(): bool
    {
        return $this->hourly;
    }

    public function setHourly(bool $hourly): self
    {
        $this->hourly = $hourly;

        return $this;
    }

    public function isWeatherCode(): bool
    {
        return $this->weatherCode;
    }

    public function setWeatherCode(bool $weatherCode): self
    {
        $this->weatherCode = $weatherCode;

        return $this;
    }

    public function isWindSpeed10m(): bool
    {
        return $this->windSpeed10m;
    }

    public function setWindSpeed10m(bool $windSpeed10m): self
    {
        $this->windSpeed10m = $windSpeed10m;

        return $this;
    }

    public function getWindSpeedUnit(): string
    {
        return $this->windSpeedUnit;
    }

    public function setWindSpeedUnit(string $windSpeedUnit): self
    {
        $this->windSpeedUnit = $windSpeedUnit;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
