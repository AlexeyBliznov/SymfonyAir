<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Flight;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function remove(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFlight(Flight $flight): ?array
    {
        return $this->createQueryBuilder('t')
            ->where('t.flight = :flight')
            ->setParameter(':flight', $flight)
            ->getQuery()->getResult();
    }

    public function removeByFlight(Flight $flight): void
    {
        $this->createQueryBuilder('t')
            ->delete('t')
            ->where('t.flight_id = :flight')
            ->setParameter(':flight', $flight->getId())
            ->getQuery()->execute();
    }

    public function findByTicketNumber(int $number): ?Ticket
    {
        return $this->findOneBy(['ticketNumber' => $number]);
    }
}