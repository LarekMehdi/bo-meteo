<?php

namespace App\Repository;

use App\Dto\Inputs\HistoryFilterDto;
use App\Entity\SearchForecastHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchForecastHistory>
 */
class SearchForecastHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchForecastHistory::class);
    }

    /**
     * @return SearchForecastHistory[]
     */
    public function findAllByUser(User $user, HistoryFilterDto $filter): array
    {
        $qb = $this->createQueryBuilder('h')
        ->andWhere('h.user = :user')
        ->setParameter('user', $user)
        ->orderBy('h.createdAt', 'DESC')
        ->setFirstResult($filter->getOffset())
        ->setMaxResults($filter->limit);

        if (null !== $filter->latitude) {
            $qb->andWhere('h.latitude = :lat')
               ->setParameter('lat', $filter->latitude);
        }

        if (null !== $filter->longitude) {
            $qb->andWhere('h.longitude = :lon')
               ->setParameter('lon', $filter->longitude);
        }

        return $qb->getQuery()->getResult();
    }

    /** COUNT **/
    public function countByUser(User $user, HistoryFilterDto $filter): int
    {
        $qb = $this->createQueryBuilder('h')
            ->select('COUNT(h.id)')
            ->andWhere('h.user = :user')
            ->setParameter('user', $user);

        if (null !== $filter->latitude) {
            $qb->andWhere('h.latitude = :lat')
               ->setParameter('lat', $filter->latitude);
        }

        if (null !== $filter->longitude) {
            $qb->andWhere('h.longitude = :lon')
               ->setParameter('lon', $filter->longitude);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    //    /**
    //     * @return SearchForecastHistory[] Returns an array of SearchForecastHistory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SearchForecastHistory
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
