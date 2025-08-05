<?php

namespace App\Service;

use App\Entity\Charging;
use App\Repository\ChargingRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ChargingService
{
    public function __construct(
        private ChargingRepository $chargingRepository,
        private Security $security
    ) {}

    public function getChargings(): array
    {
        return $this->security->getUser()->getChargings()->toArray();
    }

    public function createCharging(Charging $charging): Charging
    {
        $charging->setOwner($this->security->getUser());
        $charging->setTotalCost($this->calculateTotalCost($charging));

        $this->chargingRepository->save($charging, true);

        return $charging;
    }

    public function editCharging(Charging $charging): Charging
    {
        $charging->setTotalCost($this->calculateTotalCost($charging));

        $this->chargingRepository->save($charging, true);

        return $charging;
    }

    public function removeCharging(Charging $charging): void
    {
        $this->chargingRepository->remove($charging, true);
    }

    private function calculateTotalCost(Charging $charging): float
    {
        return $charging->getTotalKwh() * $charging->getStation()->getElectricityPrice();
    }
}
