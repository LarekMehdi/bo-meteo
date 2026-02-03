<?php

namespace App\Service;

use App\Dto\Inputs\ForecastFilterDto;
use App\Entity\SearchForecastHistory;
use App\Entity\User;
use App\Repository\SearchForecastHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

final class SearchForecastHistoryService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SearchForecastHistoryRepository $historyRepository,
    ) {
    }

    /** CREATE **/
    public function create(ForecastFilterDto $dto, User $user): SearchForecastHistory
    {
        $history = new SearchForecastHistory(
            user: $user,
            latitude: $dto->getLatitude(),
            longitude: $dto->getLongitude(),
            hourly: $dto->isHourly(),
            weatherCode: $dto->isWeatherCode(),
            windSpeed10m: $dto->isWindSpeed10m(),
            windSpeedUnit: $dto->getWindSpeedUnit(),
        );

        $this->em->persist($history);
        $this->em->flush();

        return $history;
    }
}
