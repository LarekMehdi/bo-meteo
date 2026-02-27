<?php

namespace App\Service;

use App\Dto\Inputs\ForecastFilterDto;
use App\Dto\Inputs\HistoryFilterDto;
use App\Dto\Outputs\PageDto;
use App\Entity\SearchForecastHistory;
use App\Entity\User;
use App\Exception\PreconditionFailedException;
use App\Repository\SearchForecastHistoryRepository;
use App\Utils\UtilHash;
use App\Utils\UtilMapper;
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
    public function findAllByUser(User $user, HistoryFilterDto $filter): PageDto
    {
        $entities = $this->historyRepository->findAllByUser($user, $filter);
        $total = $this->historyRepository->countByUser($user, $filter);

        $dtos = UtilMapper::mapHistoriesToHistoryDtos($entities);

        return new PageDto($dtos, $total);
    }

    /** CREATE **/
    public function create(ForecastFilterDto $dto, User $user): SearchForecastHistory
    {
        $existingCount = $this->historyRepository->countExisting($user, $dto);
        if ($existingCount > 0) {
            throw new PreconditionFailedException('An identical search already exists');
        }

        $hash = UtilHash::generateHashForHistory($dto);

        $history = new SearchForecastHistory(
            user: $user,
            latitude: $dto->getLatitude(),
            longitude: $dto->getLongitude(),
            hourly: $dto->isHourly(),
            weatherCode: $dto->isWeatherCode(),
            windSpeed10m: $dto->isWindSpeed10m(),
            windSpeedUnit: $dto->getWindSpeedUnit(),
            hash: $hash,
        );

        $history->setCityName($dto->getCityName());

        $this->em->persist($history);
        $this->em->flush();

        return $history;
    }

    /** DELETE **/
    public function delete(int $id, User $user): void
    {
        $history = $this->historyRepository->find($id);

        if (!$history) {
            throw new NotFoundHttpException("History with ID $id not found.");
        }

        if ($history->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('You are not allowed to delete this history.');
        }

        $this->em->remove($history);
        $this->em->flush();
    }
}
