<?php

namespace App\Service;

use App\Dto\Inputs\ForecastFilterDto;
use App\Entity\SearchForecastHistory;
use App\Entity\User;
use App\Repository\SearchForecastHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SearchForecastHistoryService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SearchForecastHistoryRepository $historyRepository,
    ) {
    }

    /** FIND ALL **/
    public function findAllByUser(User $user): array
    {
        $entities = $this->historyRepository->findAllByUser($user);

        return \UtilMapper::mapHistoriesToHistoryDtos($entities);
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

    /** DELETE **/
    public function delete(int $id, User $user): void
    {
        $history = $this->historyRepository->find($id);

        if (!$history) {
            throw new NotFoundHttpException('History not found');
        }

        if ($history->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('You cannot delete this history');
        }

        $this->em->remove($history);
        $this->em->flush();
    }
}
