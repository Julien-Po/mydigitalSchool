<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Calendar>
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }


    /**
     * @param \DateTimeInterface $date
     * @return Calendar[] Returns an array of Calendar objects
     */
    public function findCalendarsByStartDate(\DateTimeInterface $date): array
    {
        $startOfDay = new \DateTime($date->format('Y-m-d 00:00:00'));
        $endOfDay = new \DateTime($date->format('Y-m-d 23:59:59'));

        return $this->createQueryBuilder('c')
            ->andWhere('c.start >= :startOfDay')
            ->andWhere('c.start <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Calendar[] Returns an array of Calendar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Calendar
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
