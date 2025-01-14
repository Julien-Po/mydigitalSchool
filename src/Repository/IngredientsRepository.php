<?php

namespace App\Repository;

use App\Entity\Ingredients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;


/**
 * @extends ServiceEntityRepository<Ingredients>
 */
class IngredientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredients::class);
    }

    public function findRandomIngredient(): ?Ingredients

    {
        $count = $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

            if ($count == 0) {
                return null;
            }

            $offset = mt_rand(0, $count - 1);
            
            $query = $this->createQueryBuilder('i')
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }      
    }

       /**
        * @return Ingredients[] Returns an array of Ingredients objects
        */
        public function findIngredients(): array
        {
            return $this->createQueryBuilder('i')
                ->select("i.id, i.name, i.image, i.allergene")
                ->getQuery()
                ->getResult();
        }
        

    //    public function findOneBySomeField($value): ?Ingredients
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
