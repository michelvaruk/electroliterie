<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findActiveByPromotion($promotion): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productPromotions', 'productPromotion')
            ->andWhere('productPromotion.promotion = :promo')
            ->andWhere('p.active = true')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->setParameter('promo', $promotion)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearchString(string $searchString): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->innerJoin('p.brand', 'b');

        $terms = explode(' ', $searchString);

        foreach ($terms as $index => $term) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('p.title', ":term_$index"),
                    $queryBuilder->expr()->like('b.title', ":term_$index"),
                    $queryBuilder->expr()->like('p.reference', ":term_$index"),
                    $queryBuilder->expr()->like('p.EAN', ":term_$index")
                ))
                ->setParameter("term_$index", '%' . $term . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }


    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
