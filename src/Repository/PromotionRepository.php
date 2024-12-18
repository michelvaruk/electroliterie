<?php

namespace App\Repository;

use App\Entity\Promotion;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * @return Promotion[] Returns an array of Promotion objects
     */
    public function findActivePromotions(): array
    {
        $date = new DateTime();
        return $this->createQueryBuilder('p')
            ->andWhere('p.startDate <= :date')
            ->andWhere('p.endDate >= :date')
            ->setParameter('date', $date)
            ->orderBy('p.odr', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Promotion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
