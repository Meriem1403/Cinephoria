<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Room;
use App\Entity\Showtime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Showtime>
 */
class ShowtimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Showtime::class);
    }

    /**
     * Indique si une séance existe déjà pour ce film, cette salle, cette date et cette heure de début.
     */
    public function existsForSlot(Movie $movie, Room $room, \DateTimeInterface $date, \DateTimeInterface $startTime): bool
    {
        $qb = $this->createQueryBuilder('s')
            ->select('1')
            ->where('s.movie = :movie')
            ->andWhere('s.room = :room')
            ->andWhere('s.date = :date')
            ->andWhere('s.startTime = :startTime')
            ->setParameter('movie', $movie)
            ->setParameter('room', $room)
            ->setParameter('date', $date)
            ->setParameter('startTime', $startTime)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }

//    /**
//     * @return Showtime[] Returns an array of Showtime objects
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

//    public function findOneBySomeField($value): ?Showtime
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
