<?php

namespace App\Service;

use App\Entity\Station;
use App\Repository\StationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class StationService
{
    public function __construct(
        private StationRepository $stationRepository,
        private Security $security
    ) {}

    public function getStations(): array
    {
        return $this->security->getUser()->getStations()->toArray();
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
