<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class EntityFetcherService
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator
    ) {}

    public function fetchByOwner(
        string $entityClass,
        User $owner,
        int $page = 1,
        int $limit = 10,
        string $sortField = 'createdAt',
        string $sortDirection = 'DESC'
    ): PaginationInterface {
        $query = $this->em->createQueryBuilder()
            ->select('e')
            ->from($entityClass, 'e')
            ->andWhere('e.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('e.' . $sortField, $sortDirection);

        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
        );
    }
}
