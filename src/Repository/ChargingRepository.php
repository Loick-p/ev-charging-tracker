<?php

namespace App\Repository;

use App\Entity\Charging;
use App\Entity\User;
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
        User $owner,
        ?\DateTimeInterface $startDate = null,
        ?\DateTimeInterface $endDate = null
    ): array {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total_chargings')
            ->addSelect('SUM(c.totalKwh) as total_kwh')
            ->addSelect('SUM(c.totalCost) as total_cost')
            ->where('c.owner = :owner')
            ->setParameter('owner', $owner);

        if ($startDate && $endDate) {
            $qb->andWhere('c.date BETWEEN :start AND :end')
                ->setParameter('start', $startDate->setTime(0, 0, 0))
                ->setParameter('end', $endDate->setTime(23, 59, 59));
        }

        return $qb->getQuery()->getSingleResult();
    }

    public function findLastChargingByOwner(User $owner, int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
