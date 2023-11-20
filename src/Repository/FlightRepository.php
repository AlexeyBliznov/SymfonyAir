<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Airplane;
use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flight::class);
    }

    public function remove(Flight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchFlight(string $from, string $to, string $date): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.pointOfDeparture = :from')
            ->setParameter(':from', $from)
            ->andWhere('f.arrivalPoint = :to')
            ->setParameter(':to', $to)
            ->andWhere('f.date = :date')
            ->setParameter(':date', $date)
            ->getQuery()->getResult();
    }

    public function searchFlightByFromAndDate(string $from, string $date): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.pointOfDeparture = :from')
            ->setParameter(':from', $from)
            ->andWhere('f.date = :date')
            ->setParameter(':date', $date)
            ->getQuery()->getResult();
    }

    public function searchFlightByFromAndTo(string $from, string $to): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.pointOfDeparture = :from')
            ->setParameter(':from', $from)
            ->andWhere('f.arrivalPoint = :to')
            ->setParameter(':to', $to)
            ->getQuery()->getResult();
    }

    public function searchFlightByFrom(string $from): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.pointOfDeparture = :from')
            ->setParameter(':from', $from)
            ->getQuery()->getResult();
    }

    public function searchFlightByDate(string $date): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.date = :date')
            ->setParameter(':date', $date)
            ->getQuery()->getResult();
    }

    public function removeByAirplane(Airplane $airplane): void
    {
        $this->createQueryBuilder('f')
            ->delete('f')
            ->where('f.airplane = :airplane')
            ->setParameter(':airplane', $airplane)
            ->getQuery()->execute();
    }

    public function findById(int $id): ?Flight
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByAirplane(Airplane $airplane): ?Flight
    {
        return $this->findOneBy(['airplane' => $airplane]);
    }
}
