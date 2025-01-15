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
 * @return Calendar[] Returns an array of Calendar objects with their Recipes
 */
public function findCalendarsByDate(\DateTimeInterface $date): array
{
    if (!$date instanceof \DateTimeImmutable) {
        $date = \DateTimeImmutable::createFromMutable($date);
    }

    $startOfDay = $date->setTime(0, 0, 0);
    $endOfDay = $date->setTime(23, 59, 59);

    return $this->createQueryBuilder('c')
        ->leftJoin('c.recipes', 'r') // Join recipes associated with calendars
        ->addSelect('r') // Add recipes to the selection
        ->andWhere('c.start >= :startOfDay')
        ->andWhere('c.start <= :endOfDay')
        ->setParameter('startOfDay', $startOfDay)
        ->setParameter('endOfDay', $endOfDay)
        ->getQuery()
        ->getResult();
}





   /**
    * @return Calendar[] Returns an array of Calendar objects
    */
    public function findCalendar(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }
    
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
