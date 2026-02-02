<?php

namespace App\Entity;

use App\Repository\SearchForecastHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchForecastHistoryRepository::class)]
class SearchForecastHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private float $latitude;

    #[ORM\Column]
    private float $longitude;

    #[ORM\Column]
    private bool $hourly;

    #[ORM\Column]
    private bool $weatherCode;

    #[ORM\Column]
    private bool $windSpeed10m;

    #[ORM\Column]
    private string $windSpeedUnit;

    public function __construct(
        User $user,
        float $latitude,
        float $longitude,
        bool $hourly = true,
        bool $weatherCode = true,
        bool $windSpeed10m = true,
        string $windSpeedUnit = 'kmh',
    ) {
        $this->user = $user;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->hourly = $hourly;
        $this->weatherCode = $weatherCode;
        $this->windSpeed10m = $windSpeed10m;
        $this->windSpeedUnit = $windSpeedUnit;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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

    public function isHourly(): bool
    {
        return $this->hourly;
    }

    public function setHourly(bool $hourly): static
    {
        $this->hourly = $hourly;

        return $this;
    }

    public function isWeatherCode(): bool
    {
        return $this->weatherCode;
    }

    public function setWeatherCode(bool $weatherCode): static
    {
        $this->weatherCode = $weatherCode;

        return $this;
    }

    public function isWindSpeed10m(): bool
    {
        return $this->windSpeed10m;
    }

    public function setWindSpeed10m(bool $windSpeed10m): static
    {
        $this->windSpeed10m = $windSpeed10m;

        return $this;
    }

    public function getWindSpeedUnit(): string
    {
        return $this->windSpeedUnit;
    }

    public function setWindSpeedUnit(string $windSpeedUnit): static
    {
        $this->windSpeedUnit = $windSpeedUnit;

        return $this;
    }
}
