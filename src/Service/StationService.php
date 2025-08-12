<?php

namespace App\Service;

use App\Entity\Station;
use App\Repository\StationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;

class StationService
{
    public function __construct(
        private StationRepository $stationRepository,
        private EntityFetcherService $fetcher,
        private Security $security
    ) {}

    public function getStations(int $page = 1, int $limit = 10): PaginationInterface
    {
        return $this->fetcher->fetchByOwner(
            Station::class,
            $this->security->getUser(),
            $page,
            $limit,
        );
    }

    public function createStation(Station $station): Station
    {
        $station->setOwner($this->security->getUser());

        $this->stationRepository->save($station, true);

        return $station;
    }

    public function editStation(Station $station): Station
    {
        $this->stationRepository->save($station, true);

        return $station;
    }

    public function removeStation(Station $station): void
    {
        $this->stationRepository->remove($station, true);
    }
}
