<?php

namespace App\Repository;

use App\Entity\Recipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipes>
 */
class RecipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipes::class);
    }


    /**
     * @return Recipes[] Returns an array of Recipes objects
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.User = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @param \DateTimeInterface $date
     * @return Recipes[] Returns an array of Recipes objects
     */
    public function findRecipesByStartDate(\DateTimeInterface $date): array
    {
        $startOfDay = new \DateTime($date->format('Y-m-d 00:00:00'));
        $endOfDay = new \DateTime($date->format('Y-m-d 23:59:59'));

        return $this->createQueryBuilder('r')
            ->andWhere('r.start >= :startOfDay')
            ->andWhere('r.start <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Recipes[] Returns an array of Recipes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipes
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
