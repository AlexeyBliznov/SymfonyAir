<?php

namespace App\Repository;

use App\Entity\SoldTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SoldTicket>
 *
 * @method SoldTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoldTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoldTicket[]    findAll()
 * @method SoldTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoldTicket::class);
    }

    public function save(SoldTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SoldTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByTicketNumber(int $number): ?SoldTicket
    {
        return $this->findOneBy(['ticketNumber' => $number]);
    }

//    /**
//     * @return SoldTicket[] Returns an array of SoldTicket objects
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

//    public function findOneBySomeField($value): ?SoldTicket
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
