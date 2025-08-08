<?php

namespace App\Repository;

use App\Entity\Charging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Charging>
 */
class ChargingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Charging::class);
    }

    public function save(Charging $charging, bool $flush = false): void
    {
        $this->getEntityManager()->persist($charging);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Charging $charging, bool $flush = false): void
    {
        $this->getEntityManager()->remove($charging);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOwnerChargingStats(
        int $ownerId,
        ?\DateTimeInterface $startDate = null,
        ?\DateTimeInterface $endDate = null
    ): array {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total_chargings')
            ->addSelect('SUM(c.totalKwh) as total_kwh')
            ->addSelect('SUM(c.totalCost) as total_cost')
            ->where('c.owner = :ownerId')
            ->setParameter('ownerId', $ownerId);

        if ($startDate && $endDate) {
            $qb->andWhere('c.date BETWEEN :start AND :end')
                ->setParameter('start', $startDate->setTime(0, 0, 0))
                ->setParameter('end', $endDate->setTime(23, 59, 59));
        }

        return $qb->getQuery()->getSingleResult();
    }

//    /**
//     * @return Charging[] Returns an array of Charging objects
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

//    public function findOneBySomeField($value): ?Charging
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
